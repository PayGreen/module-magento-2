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
 * @version   2.0.0
 *
 */

/**
 * Class PGPaymentFoundationsProcessorsAbstractTransactionManagementProcessor
 * @package PGPayment\Foundations\Processors
 */
class PGPaymentFoundationsProcessorsAbstractTransactionManagementProcessor extends PGFrameworkFoundationsAbstractProcessor
{
    /** @var PGShopInterfacesOfficersPostPayment */
    protected $officer;

    /**
     * PGPaymentFoundationsProcessorsAbstractTransactionManagementProcessor constructor.
     */
    public function __construct(PGModuleServicesBroadcaster $broadcaster)
    {
        $this->broadcaster = $broadcaster;
        $this->setSteps(array(
            'default'
        ));
    }

    /**
     * @param PGShopInterfacesOfficersPostPayment $officer
     */
    public function setPostPaymentOfficer(PGShopInterfacesOfficersPostPayment $officer)
    {
        $this->officer = $officer;
    }

    /**
     * @param PGPaymentComponentsTasksTransactionManagement $task
     * @throws Exception
     */
    protected function refusedPaymentStep(PGPaymentComponentsTasksTransactionManagement $task)
    {
        /** @var PGModuleServicesHandlersBehavior $behaviors */
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
     * @param PGPaymentComponentsTasksTransactionManagement $task
     */
    protected function insertTransactionStep(PGPaymentComponentsTasksTransactionManagement $task)
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGPaymentServicesManagersTransactionManager $transactionManager */
        $transactionManager = $this->getService('manager.transaction');

        try {
            /** @var PGPaymentInterfacesEntitiesTransactionInterface|null $transaction */
            $transaction = $transactionManager->getByPid($task->getPid());

            if ($transaction === null) {
                $transaction = $transactionManager->create(
                    $task->getPid(),
                    $task->getOrder(),
                    $task->getOrderStatus(),
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
     * @param PGPaymentComponentsTasksTransactionManagement $task
     */
    protected function checkAmountValidityStep(PGPaymentComponentsTasksTransactionManagement $task)
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        if ($task->getTransaction()->getUserAmount() !== $task->getProvisioner()->getUserAmount()) {
            $logger->error(
                'PayGreen fraud check notice',
                array(
                    'paygreen-amount' => $task->getTransaction()->getUserAmount(),
                    'local-amount' => $task->getProvisioner()->getUserAmount()
                )
            );

            $task->setOrderStatus('VERIFY');
        }
    }

    /**
     * @param PGPaymentComponentsTasksTransactionManagement $task
     * @param string $name
     * @throws Exception
     */
    protected function sendOrderEventStep(PGPaymentComponentsTasksTransactionManagement $task, $name)
    {
        /** @var PGModuleServicesBroadcaster $broadcaster */
        $broadcaster = $this->getService('broadcaster');

        $event = new PGShopComponentsEventsOrder($name, $task->getPid(), $task->getOrder());

        $broadcaster->fire($event);
    }

    /**
     * @param PGPaymentComponentsTasksTransactionManagement $task
     * @throws Exception
     */
    protected function saveOrderStep(PGPaymentComponentsTasksTransactionManagement $task)
    {
        /** @var PGShopInterfacesEntitiesOrder|null $order */
        $order = $task->hasOrder() ? $task->getOrder() : $this->officer->getOrder($task->getProvisioner());

        if ($order === null) {
            $order = $this->createOrder($task);
        } else {
            $this->updateOrder($order, $task);
        }

        $task->setOrder($order);
    }

    /**
     * @param PGPaymentComponentsTasksTransactionManagement $task
     * @return PGShopInterfacesEntitiesOrder|null
     * @throws Exception
     */
    private function createOrder(PGPaymentComponentsTasksTransactionManagement $task)
    {
        /** @var PGShopServicesManagersOrderState $orderStateManager */
        $orderStateManager = $this->getService('manager.order_state');

        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        $order = null;

        if ($orderStateManager->isAllowedStart($task->getTransaction()->getMode(), $task->getOrderStatus())) {
            $order = $this->officer->createOrder(
                $task->getProvisioner(),
                $task->getOrderStatus()
            );
        } else {
            $logger->error("Unauthorized start state: '{$task->getOrderStatus()}'.");
            $task->setStatus($task::STATE_WORKFLOW_ERROR);
        }

        return $order;
    }

    /**
     * @param PGShopInterfacesEntitiesOrder $order
     * @param PGPaymentComponentsTasksTransactionManagement $task
     * @throws Exception
     */
    private function updateOrder(
        PGShopInterfacesEntitiesOrder $order,
        PGPaymentComponentsTasksTransactionManagement $task
    ) {
        /** @var PGShopServicesManagersOrder $orderManager */
        $orderManager = $this->getService('manager.order');

        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        try {
            $orderManager->updateOrder($order, $task->getOrderStatus(), $task->getTransaction()->getMode());
        } catch (PGShopExceptionsUnnecessaryOrderTransition $exception) {
            $logger->info($exception->getMessage());
            $this->addException($exception);
            $task->setStatus($task::STATE_UNNECESSARY_TASK);
        } catch (PGShopExceptionsUnauthorizedOrderTransition $exception) {
            $logger->error($exception->getMessage());
            $this->addException($exception);
            $task->setStatus($task::STATE_WORKFLOW_ERROR);
        }
    }

    /**
     * @param PGPaymentComponentsTasksTransactionManagement $task
     */
    protected function checkTestingModeStep(PGPaymentComponentsTasksTransactionManagement $task)
    {
        if ($task->getTransaction()->isTesting() && (PAYGREEN_ENV !== 'DEV')) {
            $task->setOrderStatus('TEST');
        }
    }

    /**
     * @param PGPaymentComponentsTasksTransactionManagement $task
     * @param string $status
     */
    protected function setOrderStatusStep(PGPaymentComponentsTasksTransactionManagement $task, $status)
    {
        $task->setOrderStatus($status);
    }

    /**
     * @param PGPaymentComponentsTasksTransactionManagement $task
     */
    protected function loadOrderStep(PGPaymentComponentsTasksTransactionManagement $task)
    {
        /** @var PGShopInterfacesEntitiesOrder|null $order */
        $order = $this->officer->getOrder($task->getProvisioner());

        if ($order !== null) {
            $task->setOrder($order);
        }
    }

    /**
     * @param PGPaymentComponentsTasksTransactionManagement $task
     * @throws Exception
     */
    protected function cancelExistingOrderStep(PGPaymentComponentsTasksTransactionManagement $task)
    {
        /** @var PGModuleServicesHandlersBehavior $behaviors */
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
