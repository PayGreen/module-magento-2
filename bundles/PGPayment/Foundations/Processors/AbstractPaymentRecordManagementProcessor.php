<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGPayment\Foundations\Processors;

use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGPayment\Components\Tasks\TransactionManagement as TransactionManagementTaskComponent;
use PGI\Module\PGPayment\Foundations\Processors\AbstractTransactionManagementProcessor;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use PGI\Module\PGPayment\Services\Managers\RecurringTransactionManager;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use Exception;

/**
 * Class AbstractPaymentRecordManagementProcessor
 * @package PGPayment\Foundations\Processors
 */
abstract class AbstractPaymentRecordManagementProcessor extends AbstractTransactionManagementProcessor
{
    protected function defaultStep(TransactionManagementTaskComponent $task)
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

    protected function paymentRecordWorkflowStep(TransactionManagementTaskComponent $task)
    {
        switch ($task->getTransaction()->getResult()->getStatus()) {
            case PaygreenFacade::STATUS_REFUSED:
            case PaygreenFacade::STATUS_CANCELLING:
                $this->pushStep('refusedPayment');
                break;

            case PaygreenFacade::STATUS_SUCCESSED:
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

    protected function transactionWorkflowStep(TransactionManagementTaskComponent $task)
    {
        switch ($task->getTransaction()->getResult()->getStatus()) {
            case PaygreenFacade::STATUS_REFUSED:
            case PaygreenFacade::STATUS_CANCELLING:
                $this->pushSteps(array(
                    'refusedTransaction',
                    array('setStatus', array(
                        $task::STATE_SUCCESS
                    ))
                ));

                break;

            case PaygreenFacade::STATUS_SUCCESSED:
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

    protected function loadRequiredOrderStep(TransactionManagementTaskComponent $task)
    {
        /** @var OrderEntityInterface|null $order */
        $order = $this->officer->getOrder($task->getProvisioner());

        if ($order === null) {
            $task->setStatus($task::STATE_ORDER_NOT_FOUND);
        } else {
            $task->setOrder($order);
        }
    }

    protected function insertRecurringTransactionStep(TransactionManagementTaskComponent $task)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        /** @var RecurringTransactionManager $recurringTransactionManager */
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

    protected function finalizeRecurringTransactionStep(TransactionManagementTaskComponent $task)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        /** @var RecurringTransactionManager $recurringTransactionManager */
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
