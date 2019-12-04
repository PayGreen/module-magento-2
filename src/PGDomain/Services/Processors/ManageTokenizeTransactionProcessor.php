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
 * @version   0.3.3
 */

/**
 * Class PGDomainServicesProcessorsManageTokenizeTransactionProcessor
 * @package PGDomain\Services\Processors
 */
class PGDomainServicesProcessorsManageTokenizeTransactionProcessor extends PGDomainFoundationsProcessorsAbstractTransactionManagementProcessor
{
    const PROCESSOR_NAME = 'TokenizeTransaction';

    protected function defaultStep(PGDomainTasksTransactionManagementTask $task)
    {
        /** @var PGDomainInterfacesEntitiesOrderInterface|null $order */
        $order = $this->officer->getOrder($task->getProvisioner());

        $task->setOrder($order);

        if (($order !== null) && ($order->getState() === 'AUTH')) {
            $this->pushStep('confirmationWorkflow');
        } else {
            $this->pushStep('creationWorkflow');
        }
    }

    protected function creationWorkflowStep(PGDomainTasksTransactionManagementTask $task)
    {
        switch ($task->getTransaction()->getResult()->getStatus()) {
            case PGDomainServicesPaygreenFacade::STATUS_REFUSED:
                $this->pushStep('refusedPayment');
                break;

            case PGDomainServicesPaygreenFacade::STATUS_PENDING_EXEC:
                $this->pushSteps(array(
                    array('setOrderStatus', array('AUTH')),
                    'saveOrder',
                    'insertTransaction',
                    array('setStatus', array(
                        $task::STATE_SUCCESS
                    ))
                ));

                break;

            default:
                $task->setStatus($task::STATE_WORKFLOW_ERROR);
        }
    }

    protected function confirmationWorkflowStep(PGDomainTasksTransactionManagementTask $task)
    {
        switch ($task->getTransaction()->getResult()->getStatus()) {
            case PGDomainServicesPaygreenFacade::STATUS_REFUSED:
                $this->pushSteps(array(
                    array('setOrderStatus', array('ERROR')),
                    'saveOrder',
                    'updateTransaction',
                    array('setStatus', array(
                        $task::STATE_PAYMENT_REFUSED
                    ))
                ));

                break;

            case PGDomainServicesPaygreenFacade::STATUS_SUCCESSED:
                $this->pushSteps(array(
                    array('setOrderStatus', array('VALIDATE')),
                    'checkAmountValidity',
                    'checkTestingMode',
                    'saveOrder',
                    'updateTransaction',
                    array('sendOrderEvent', array('VALIDATION')),
                    array('setStatus', array(
                        $task::STATE_SUCCESS
                    ))
                ));

                break;

            default:
                $task->setStatus($task::STATE_WORKFLOW_ERROR);
        }
    }

    protected function updateTransactionStep(PGDomainTasksTransactionManagementTask $task)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGDomainServicesManagersTransactionManager $transactionManager */
        $transactionManager = $this->getService('manager.transaction');

        try {
            $transactionManager->updateTransaction(
                $task->getPid(),
                $task->getOrderStatus()
            );
        } catch (Exception $exception) {
            $this->addException($exception);
            $logger->error('Error on update transaction : ' . $exception->getMessage(), $exception);
        }
    }
}
