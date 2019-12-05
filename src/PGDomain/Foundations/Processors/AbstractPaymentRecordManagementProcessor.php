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
 * Class PGDomainFoundationsProcessorsAbstractPaymentRecordManagementProcessor
 * @package PGDomain\Foundations\Processors
 */
abstract class PGDomainFoundationsProcessorsAbstractPaymentRecordManagementProcessor extends PGDomainFoundationsProcessorsAbstractTransactionManagementProcessor
{
    protected function defaultStep(PGDomainTasksTransactionManagementTask $task)
    {
        switch ($task->getTransaction()->getTransactionType()) {
            case 'PR':
                $this->pushStep('paymentRecordWorkflow');
                break;

            case 'TR':
                $this->pushSteps(array(
                    'loadRequiredOrder',
                    'insertRecurringTransaction',
                    'transactionWorkflow'
                ));
                break;

            default:
                $task->setStatus($task::STATE_WORKFLOW_ERROR);
        }
    }

    protected function paymentRecordWorkflowStep(PGDomainTasksTransactionManagementTask $task)
    {
        switch ($task->getTransaction()->getResult()->getStatus()) {
            case PGDomainServicesPaygreenFacade::STATUS_REFUSED:
                $this->pushStep('refusedPayment');
                break;

            case PGDomainServicesPaygreenFacade::STATUS_SUCCESSED:
                $this->pushSteps(array(
                    array('setOrderStatus', array('WAIT')),
                    'saveOrder',
                    'insertTransaction',
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

    protected function transactionWorkflowStep(PGDomainTasksTransactionManagementTask $task)
    {
        switch ($task->getTransaction()->getResult()->getStatus()) {
            case PGDomainServicesPaygreenFacade::STATUS_REFUSED:
                $this->pushSteps(array(
                    'refusedTransaction',
                    array('setStatus', array(
                        $task::STATE_SUCCESS
                    ))
                ));

                break;

            case PGDomainServicesPaygreenFacade::STATUS_SUCCESSED:
                $this->pushSteps(array(
                    array('setOrderStatus', array('VALIDATE')),
                    'checkTestingMode',
                    'saveOrder',
                    'finalizeRecurringTransaction',
                    array('setStatus', array(
                        $task::STATE_SUCCESS
                    ))
                ));

                break;

            default:
                $task->setStatus($task::STATE_WORKFLOW_ERROR);
        }
    }

    protected function loadRequiredOrderStep(PGDomainTasksTransactionManagementTask $task)
    {
        /** @var PGDomainInterfacesEntitiesOrderInterface|null $order */
        $order = $this->officer->getOrder($task->getProvisioner());

        if ($order === null) {
            $task->setStatus($task::STATE_ORDER_NOT_FOUND);
        } else {
            $task->setOrder($order);
        }
    }

    protected function insertRecurringTransactionStep(PGDomainTasksTransactionManagementTask $task)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGDomainServicesManagersRecurringTransactionManager $recurringTransactionManager */
        $recurringTransactionManager = $this->getService('manager.recurring_transaction');

        $transaction = $recurringTransactionManager->getByPid($task->getPid());

        if ($transaction !== null) {
            $logger->info("Recurring transaction already processed : '{$task->getPid()}'.");
            $task->setStatus($task::STATE_SUCCESS);
        } else {
            try {
                $recurringTransactionManager->insertTransaction(
                    $task->getPid(),
                    $task->getOrder()->id(),
                    $task->getTransaction()->getResult()->getStatus(),
                    $task->getOrder()->getState(),
                    $task->getTransaction()->getMode(),
                    $task->getTransaction()->getAmount(),
                    $task->getTransaction()->getRank()
                );
            } catch (Exception $exception) {
                $this->addException($exception);
                $logger->error('Error on insert recurring transaction : ' . $exception->getMessage(), $exception);
            }
        }
    }

    protected function finalizeRecurringTransactionStep(PGDomainTasksTransactionManagementTask $task)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGDomainServicesManagersRecurringTransactionManager $recurringTransactionManager */
        $recurringTransactionManager = $this->getService('manager.recurring_transaction');

        try {
            $recurringTransactionManager->updateTransaction(
                $task->getPid(),
                $task->getOrder()->getState()
            );
        } catch (Exception $exception) {
            $this->addException($exception);
            $logger->error('Error on update recurring transaction : ' . $exception->getMessage(), $exception);
        }
    }
}
