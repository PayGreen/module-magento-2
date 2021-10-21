<?php
/**
 * 2014 - 2021 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License X11
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/mit-license.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2021 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGPayment\Foundations\Processors;

use PGI\Module\PGFramework\Foundations\AbstractProcessor;
use PGI\Module\PGModule\Services\Broadcaster;
use PGI\Module\PGModule\Services\Handlers\BehaviorHandler;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGPayment\Components\Tasks\TransactionManagement as TransactionManagementTaskComponent;
use PGI\Module\PGPayment\Interfaces\Entities\TransactionEntityInterface;
use PGI\Module\PGPayment\Services\Managers\TransactionManager;
use PGI\Module\PGShop\Components\Events\Order as OrderEventComponent;
use PGI\Module\PGShop\Exceptions\UnauthorizedOrderTransition as UnauthorizedOrderTransitionException;
use PGI\Module\PGShop\Exceptions\UnnecessaryOrderTransition as UnnecessaryOrderTransitionException;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Interfaces\Officers\PostPaymentOfficerInterface;
use PGI\Module\PGShop\Services\Managers\OrderManager;
use PGI\Module\PGShop\Services\Managers\OrderStateManager;
use Exception;

/**
 * Class AbstractTransactionManagementProcessor
 * @package PGPayment\Foundations\Processors
 */
class AbstractTransactionManagementProcessor extends AbstractProcessor
{
    /** @var PostPaymentOfficerInterface */
    protected $officer;

    /**
     * AbstractTransactionManagementProcessor constructor.
     */
    public function __construct()
    {
        $this->setSteps(array(
            'default'
        ));
    }

    /**
     * @param PostPaymentOfficerInterface $officer
     */
    public function setPostPaymentOfficer(PostPaymentOfficerInterface $officer)
    {
        $this->officer = $officer;
    }

    /**
     * @param TransactionManagementTaskComponent $task
     * @throws Exception
     */
    protected function refusedPaymentStep(TransactionManagementTaskComponent $task)
    {
        /** @var BehaviorHandler $behaviors */
        $behaviors = $this->getService('handler.behavior');

        if ($behaviors->get('cancel_order_on_refused_payment')) {
            $this->pushSteps(array(
                array('setOrderStatus', array('CANCEL')),
                'saveOrder',
                array('sendOrderEvent', array('CANCELLATION')),
                array('setStatus', array(
                    $task::STATE_SUCCESS
                ))
            ));
        } else {
            $task->setStatus($task::STATE_PAYMENT_REFUSED);
        }
    }

    /**
     * @param TransactionManagementTaskComponent $task
     */
    protected function insertTransactionStep(TransactionManagementTaskComponent $task)
    {
        /** @var Logger $logger */
        $logger = $this->getService('logger');

        /** @var TransactionManager $transactionManager */
        $transactionManager = $this->getService('manager.transaction');

        try {
            /** @var TransactionEntityInterface|null $transaction */
            $transaction = $transactionManager->getByPid($task->getPid());

            if ($transaction === null) {
                $transaction = $transactionManager->create(
                    $task->getPid(),
                    $task->getOrder(),
                    $task->getOrderStateTo(),
                    $task->getTransaction()->getMode(),
                    $task->getTransaction()->getAmount()
                );

                $transactionManager->save($transaction);
            } else {
                $logger->warning("Transaction already exists for PID : " . $task->getPid());
            }
        } catch (Exception $exception) {
            $this->addException($exception);
            $logger->error('Error on insert transaction: ' . $exception->getMessage(), $exception);
        }
    }

    /**
     * @param TransactionManagementTaskComponent $task
     */
    protected function checkAmountValidityStep(TransactionManagementTaskComponent $task)
    {
        /** @var Logger $logger */
        $logger = $this->getService('logger');

        if ($task->getTransaction()->getUserAmount() !== $task->getProvisioner()->getUserAmount()) {
            $logger->error(
                'PayGreen fraud check notice',
                array(
                    'paygreen-amount' => $task->getTransaction()->getUserAmount(),
                    'local-amount' => $task->getProvisioner()->getUserAmount()
                )
            );

            $task->setOrderStateTo('VERIFY');
        }
    }

    /**
     * @param TransactionManagementTaskComponent $task
     * @param string $name
     * @throws Exception
     */
    protected function sendOrderEventStep(TransactionManagementTaskComponent $task, $name)
    {
        /** @var Broadcaster $broadcaster */
        $broadcaster = $this->getService('broadcaster');

        $event = new OrderEventComponent($name, $task->getPid(), $task->getOrder());

        $broadcaster->fire($event);
    }

    /**
     * @param TransactionManagementTaskComponent $task
     * @throws Exception
     */
    protected function saveOrderStep(TransactionManagementTaskComponent $task)
    {
        /** @var OrderEntityInterface|null $order */
        $order = $task->hasOrder() ? $task->getOrder() : $this->officer->getOrder($task->getProvisioner());

        if ($order === null) {
            $order = $this->createOrder($task);
        } else {
            $task->setOrderStateFrom($order->getState());
            $this->updateOrder($order, $task);
        }

        $task->setOrder($order);
    }

    /**
     * @param TransactionManagementTaskComponent $task
     * @return OrderEntityInterface|null
     * @throws Exception
     */
    private function createOrder(TransactionManagementTaskComponent $task)
    {
        /** @var OrderStateManager $orderStateManager */
        $orderStateManager = $this->getService('manager.order_state');

        /** @var Logger $logger */
        $logger = $this->getService('logger');

        $order = null;

        if ($orderStateManager->isAllowedStart($task->getTransaction()->getMode(), $task->getOrderStateTo())) {
            $order = $this->officer->createOrder(
                $task->getProvisioner(),
                $task->getOrderStateTo()
            );
        } else {
            $logger->error("Unauthorized start state: '{$task->getOrderStateTo()}'.");
            $task->setStatus($task::STATE_WORKFLOW_ERROR);
        }

        return $order;
    }

    /**
     * @param OrderEntityInterface $order
     * @param TransactionManagementTaskComponent $task
     * @throws Exception
     */
    private function updateOrder(
        OrderEntityInterface $order,
        TransactionManagementTaskComponent $task
    ) {
        /** @var OrderManager $orderManager */
        $orderManager = $this->getService('manager.order');

        /** @var Logger $logger */
        $logger = $this->getService('logger');

        try {
            $orderManager->updateOrder($order, $task->getOrderStateTo(), $task->getTransaction()->getMode());
        } catch (UnnecessaryOrderTransitionException $exception) {
            $logger->info($exception->getMessage());
            $this->addException($exception);
            $task->setStatus($task::STATE_UNNECESSARY_TASK);
        } catch (UnauthorizedOrderTransitionException $exception) {
            $logger->error($exception->getMessage());
            $this->addException($exception);
            $task->setStatus($task::STATE_WORKFLOW_ERROR);
        }
    }

    /**
     * @param TransactionManagementTaskComponent $task
     */
    protected function checkTestingModeStep(TransactionManagementTaskComponent $task)
    {
        if ($task->getTransaction()->isTesting() && (PAYGREEN_ENV !== 'DEV')) {
            $task->setOrderStateTo('TEST');
        }
    }

    /**
     * @param TransactionManagementTaskComponent $task
     * @param string $status
     */
    protected function setOrderStatusStep(TransactionManagementTaskComponent $task, $status)
    {
        $task->setOrderStateTo($status);
    }

    /**
     * @param TransactionManagementTaskComponent $task
     */
    protected function loadOrderStep(TransactionManagementTaskComponent $task)
    {
        /** @var OrderEntityInterface|null $order */
        $order = $this->officer->getOrder($task->getProvisioner());

        if ($order !== null) {
            $task->setOrder($order);
        }
    }

    /**
     * @param TransactionManagementTaskComponent $task
     * @throws Exception
     */
    protected function cancelExistingOrderStep(TransactionManagementTaskComponent $task)
    {
        /** @var BehaviorHandler $behaviors */
        $behaviors = $this->getService('handler.behavior');

        if (($task->getOrder() !== null) && $behaviors->get('cancel_order_on_canceled_payment')) {
            $this->setSteps(array(
                array('setOrderStatus', array('CANCEL')),
                'saveOrder',
                array('sendOrderEvent', array('CANCELLATION')),
                array('setStatus', array(
                    $task::STATE_SUCCESS
                ))
            ));
        }
    }
}
