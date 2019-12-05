<?php
/**
 * 2014 - 2019 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.5
 */

/**
 * Class PGDomainFoundationsProcessorsAbstractTransactionManagementProcessor
 * @package PGDomain\Foundations\Processors
 */
class PGDomainFoundationsProcessorsAbstractTransactionManagementProcessor extends PGFrameworkFoundationsAbstractProcessor
{
    /** @var PGDomainInterfacesOfficersPostPaymentOfficerInterface */
    protected $officer;

    /**
     * PGDomainFoundationsProcessorsAbstractTransactionManagementProcessor constructor.
     */
    public function __construct()
    {
        $this->setSteps(array(
            'default'
        ));
    }

    /**
     * @param PGDomainInterfacesOfficersPostPaymentOfficerInterface $officer
     */
    public function setPostPaymentOfficer(PGDomainInterfacesOfficersPostPaymentOfficerInterface $officer)
    {
        $this->officer = $officer;
    }

    /**
     * @param PGDomainTasksTransactionManagementTask $task
     * @throws Exception
     */
    protected function refusedPaymentStep(PGDomainTasksTransactionManagementTask $task)
    {
        /** @var PGFrameworkServicesHandlersBehaviorHandler $behaviors */
        $behaviors = $this->getService('handler.behavior');

        if ($behaviors->get('cancel_order_on_refused_payment')) {
            $this->pushSteps(array(
                array('setOrderStatus', array('CANCEL')),
                'saveOrder',
                array('setStatus', array(
                    $task::STATE_SUCCESS
                ))
            ));
        } else {
            $task->setStatus($task::STATE_PAYMENT_REFUSED);
        }
    }

    /**
     * @param PGDomainTasksTransactionManagementTask $task
     */
    protected function insertTransactionStep(PGDomainTasksTransactionManagementTask $task)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGDomainServicesManagersTransactionManager $transactionManager */
        $transactionManager = $this->getService('manager.transaction');

        try {
            /** @var PGDomainInterfacesEntitiesTransactionInterface $transaction */
            $transaction = $transactionManager->create(
                $task->getPid(),
                $task->getOrder(),
                $task->getOrderStatus(),
                $task->getTransaction()->getMode(),
                $task->getTransaction()->getAmount()
            );

            $transactionManager->save($transaction);
        } catch (Exception $exception) {
            $this->addException($exception);
            $logger->error('Error on insert transaction :' . $exception->getMessage(), $exception);
        }
    }

    /**
     * @param PGDomainTasksTransactionManagementTask $task
     */
    protected function checkAmountValidityStep(PGDomainTasksTransactionManagementTask $task)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        if ($task->getTransaction()->getUserAmount() !== $task->getProvisioner()->getUserAmount()) {
            $logger->error(
                'Paygreen fraud check notice',
                array(
                    'paygreen-amount' => $task->getTransaction()->getUserAmount(),
                    'local-amount' => $task->getProvisioner()->getUserAmount()
                )
            );

            $task->setOrderStatus('VERIFY');
        }
    }

    /**
     * @param PGDomainTasksTransactionManagementTask $task
     * @param string $name
     * @throws Exception
     */
    protected function sendOrderEventStep(PGDomainTasksTransactionManagementTask $task, $name)
    {
        /** @var PGFrameworkServicesBroadcaster $broadcaster */
        $broadcaster = $this->getService('broadcaster');

        $event = new PGDomainComponentsEventsOrderEvent($name, $task->getPid(), $task->getOrder());

        $broadcaster->fire($event);
    }

    /**
     * @param PGDomainTasksTransactionManagementTask $task
     * @throws Exception
     */
    protected function saveOrderStep(PGDomainTasksTransactionManagementTask $task)
    {
        /** @var PGDomainInterfacesEntitiesOrderInterface|null $order */
        $order = $task->hasOrder() ? $task->getOrder() : $this->officer->getOrder($task->getProvisioner());

        if ($order === null) {
            $order = $this->createOrder($task);
        } else {
            $this->updateOrder($order, $task);
        }

        $task->setOrder($order);
    }

    /**
     * @param PGDomainTasksTransactionManagementTask $task
     * @return PGDomainInterfacesEntitiesOrderInterface|null
     * @throws Exception
     */
    private function createOrder(PGDomainTasksTransactionManagementTask $task)
    {
        /** @var PGDomainServicesManagersOrderStateManager $orderStateManager */
        $orderStateManager = $this->getService('manager.order_state');

        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $order = null;

        if ($orderStateManager->isAllowedStart($task->getTransaction()->getMode(), $task->getOrderStatus())) {
            $order = $this->officer->createOrder(
                $task->getProvisioner(),
                $task->getOrderStatus()
            );
        } else {
            $logger->error("Unauthorized start state : '{$task->getOrderStatus()}'.");
            $task->setStatus($task::STATE_WORKFLOW_ERROR);
        }

        return $order;
    }

    /**
     * @param PGDomainInterfacesEntitiesOrderInterface $order
     * @param PGDomainTasksTransactionManagementTask $task
     * @throws Exception
     */
    private function updateOrder(
        PGDomainInterfacesEntitiesOrderInterface $order,
        PGDomainTasksTransactionManagementTask $task
    ) {
        /** @var PGDomainServicesManagersOrderManager $orderManager */
        $orderManager = $this->getService('manager.order');

        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        try {
            $orderManager->updateOrder($order, $task->getOrderStatus(), $task->getTransaction()->getMode());
        } catch (PGDomainExceptionsUnnecessaryOrderTransitionException $exception) {
            $logger->info($exception->getMessage());
            $this->addException($exception);
            $task->setStatus($task::STATE_UNNECESSARY_TASK);
        } catch (PGDomainExceptionsUnauthorizedOrderTransitionException $exception) {
            $logger->error($exception->getMessage());
            $this->addException($exception);
            $task->setStatus($task::STATE_WORKFLOW_ERROR);
        }
    }

    /**
     * @param PGDomainTasksTransactionManagementTask $task
     */
    protected function checkTestingModeStep(PGDomainTasksTransactionManagementTask $task)
    {
        if ($task->getTransaction()->isTesting() && (PAYGREEN_ENV !== 'DEV')) {
            $task->setOrderStatus('TEST');
        }
    }

    /**
     * @param PGDomainTasksTransactionManagementTask $task
     * @param string $status
     */
    protected function setOrderStatusStep(PGDomainTasksTransactionManagementTask $task, $status)
    {
        $task->setOrderStatus($status);
    }

    /**
     * @param PGDomainTasksTransactionManagementTask $task
     */
    protected function loadOrderStep(PGDomainTasksTransactionManagementTask $task)
    {
        /** @var PGDomainInterfacesEntitiesOrderInterface|null $order */
        $order = $this->officer->getOrder($task->getProvisioner());

        if ($order !== null) {
            $task->setOrder($order);
        }
    }

    /**
     * @param PGDomainTasksTransactionManagementTask $task
     * @throws Exception
     */
    protected function cancelExistingOrderStep(PGDomainTasksTransactionManagementTask $task)
    {
        /** @var PGFrameworkServicesHandlersBehaviorHandler $behaviors */
        $behaviors = $this->getService('handler.behavior');

        if (($task->getOrder() !== null) && $behaviors->get('cancel_order_on_canceled_payment')) {
            $this->setSteps(array(
                array('setOrderStatus', array('CANCEL')),
                'saveOrder',
                array('setStatus', array(
                    $task::STATE_SUCCESS
                ))
            ));
        }
    }
}
