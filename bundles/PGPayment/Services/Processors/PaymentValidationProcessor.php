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
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGPayment\Services\Processors;

use PGI\Module\APIPayment\Components\Replies\Transaction as TransactionReplyComponent;
use PGI\Module\APIPayment\Services\Facades\ApiFacade;
use PGI\Module\PGFramework\Foundations\AbstractProcessor;
use PGI\Module\PGModule\Interfaces\ModuleFacadeInterface;
use PGI\Module\PGModule\Services\Handlers\BehaviorHandler;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGPayment\Components\Tasks\PaymentValidation as PaymentValidationTaskComponent;
use PGI\Module\PGPayment\Components\Tasks\TransactionManagement as TransactionManagementTaskComponent;
use PGI\Module\PGPayment\Interfaces\Entities\ProcessingEntityInterface;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use PGI\Module\PGPayment\Services\Handlers\ProcessingHandler;
use PGI\Module\PGPayment\Services\Handlers\TestingPaymentHandler;
use PGI\Module\PGPayment\Services\Managers\LockManager;
use PGI\Module\PGShop\Interfaces\Officers\PostPaymentOfficerInterface;
use Exception;

/**
 * Class PaymentValidationProcessor
 * @package PGPayment\Services\Processors
 */
class PaymentValidationProcessor extends AbstractProcessor
{
    const PROCESSOR_NAME = 'PaymentValidation';

    private static $USE_PARENT_PAYMENT_RECORD_BY_PAYMENT_MODE = array('CASH');

    /** @var PostPaymentOfficerInterface */
    protected $postPaymentOfficer;

    /** @var ProcessingHandler */
    protected $processingHandler;

    public function __construct(ProcessingHandler $processingHandler)
    {
        $this->processingHandler = $processingHandler;

        $this->setSteps(array(
            'verifyPIDValidity',
            'verifyModuleActivation',
            'putLock',
            'paygreenCall',
            'manageAbortedTransaction',
            'loadTransactionProcessorCache',
            'buildProvisioner',
            'switchPaymentMode'
        ));
    }

    /**
     * @param PostPaymentOfficerInterface $officer
     */
    public function setPostPaymentOfficer($officer)
    {
        $this->postPaymentOfficer = $officer;
    }

    protected function verifyPIDValidityStep(PaymentValidationTaskComponent $task)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        if (!$task->getPid()) {
            $logger->error('PID not found.');
            $task->setStatus($task::STATE_PID_NOT_FOUND);
        }
    }

    protected function verifyModuleActivationStep(PaymentValidationTaskComponent $task)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        /** @var ModuleFacadeInterface $moduleFacade */
        $moduleFacade = $this->getService('facade.module');

        if (!$moduleFacade->isActive()) {
            $logger->error('PayGreen module is deactivated.');
            $task->setStatus($task::STATE_INCONSISTENT_CONTEXT);
        }
    }

    /**
     * @param PaymentValidationTaskComponent $task
     * @throws Exception
     */
    protected function putLockStep(PaymentValidationTaskComponent $task)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        /** @var LockManager $lockManager */
        $lockManager = $this->getService('manager.lock');

        /** @var BehaviorHandler $behaviorHandler */
        $behaviorHandler = $this->getService('handler.behavior');

        $useTransactionLock = $behaviorHandler->get('use_transaction_lock');

        if (!$useTransactionLock) {
            $logger->debug('PID Locking is disabled.');
        } elseif ($lockManager->isLocked($task->getPid())) {
            $logger->error("Payment validation - PID locked : '{$task->getPid()}'.");
            $task->setStatus($task::STATE_PID_LOCKED);
        }
    }

    protected function paygreenCallStep(PaymentValidationTaskComponent $task)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        /** @var ApiFacade $apiFacade */
        $apiFacade = $this->getService('paygreen.facade')->getApiFacade();

        try {
            $logger->debug("Call API server to get transaction data for PID {$task->getPid()}.");

            /** @var TransactionReplyComponent $transaction */
            $transaction = $apiFacade->getTransactionInfo($task->getPid());

            $usePaymentRecord = in_array($transaction->getMode(), self::$USE_PARENT_PAYMENT_RECORD_BY_PAYMENT_MODE);

            if ($usePaymentRecord && ($transaction->getPaymentFolder() !== null)) {
                $text = "Composite transaction detected.";
                $text .= " Call API server to get payment record data for PID {$transaction->getPaymentFolder()}.";
                $logger->debug($text);

                /** @var TransactionReplyComponent $transaction */
                $transaction = $apiFacade->getTransactionInfo($transaction->getPaymentFolder());
            }

            $logger->info("Transaction state : {$transaction->getResult()->getStatus()}.");

            $task->setTransaction($transaction);
        } catch (Exception $exception) {
            $logger->error("PayGreen API error: {$exception->getMessage()}", $exception);

            $this->addException($exception);

            $task->setStatus($task::STATE_PAYGREEN_UNAVAILABLE);
        }
    }

    protected function manageAbortedTransactionStep(PaymentValidationTaskComponent $task)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        if ($task->getTransaction()->getResult()->getStatus() === PaygreenFacade::STATUS_PENDING) {
            $logger->error('Transaction cancelled by user.');
            $task->setStatus($task::STATE_PAYMENT_ABORTED);
        }
    }

    protected function loadTransactionProcessorCacheStep(PaymentValidationTaskComponent $task)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        try {
            /** @var ProcessingEntityInterface $processing */
            $processing = $this->processingHandler->loadCachedProcessingResult($task->getTransaction());

            if ($processing !== null) {
                $logger->notice('Use cached processor result.');

                $statusCode = $task->getStatusCode($processing->getStatus());
                $task->setStatus($statusCode);

                $task->setOrder($processing->getOrder());
                $task->setFinalOrderState($processing->getOrderStateTo());
            }
        } catch (Exception $exception) {
            $message = "An error occurred during processor cache management : " . $exception->getMessage();
            $logger->error($message, $exception);
        }
    }

    protected function buildProvisionerStep(PaymentValidationTaskComponent $task)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        try {
            $provisioner = $this->postPaymentOfficer->buildPostPaymentProvisioner($task->getPid(), $task->getTransaction());

            $task->setProvisioner($provisioner);
        } catch (Exception $exception) {
            $logger->error('Error during provisioner construction: ' . $exception->getMessage(), $exception);
            $task->setStatus($task::STATE_PROVIDER_ERROR);
        }
    }

    /**
     * @param PaymentValidationTaskComponent $task
     * @throws Exception
     */
    protected function switchPaymentModeStep(PaymentValidationTaskComponent $task)
    {
        /** @var TransactionManagementTaskComponent $subTask */
        $subTask = new TransactionManagementTaskComponent($task->getProvisioner());

        /** @var AbstractProcessor|null $processor */
        $processor = null;

        $paymentMode = $task->getTransaction()->getMode();

        switch ($paymentMode) {
            case 'CASH':
                $processor = $this->getService('processor.transaction_management.cash');
                break;
            case 'TOKENIZE':
                $processor = $this->getService('processor.transaction_management.tokenize');
                break;
            case 'XTIME':
                $processor = $this->getService('processor.transaction_management.xtime');
                break;
            case 'RECURRING':
                $processor = $this->getService('processor.transaction_management.recurring');
                break;
            default:
                $task->setStatus($task::STATE_WORKFLOW_ERROR);
        }

        if ($processor !== null) {
            $processor->execute($subTask);

            switch ($subTask->getStatus()) {
                case $subTask::STATE_SUCCESS:
                case $subTask::STATE_UNNECESSARY_TASK:
                case $subTask::STATE_ORDER_CANCELED:
                    $task->setStatus($task::STATE_SUCCESS);
                    $task->setOrder($subTask->getOrder());
                    $task->setFinalOrderState($subTask->getOrderStateTo());
                    $this->processingHandler->saveProcessing($subTask, true);
                    break;

                case $subTask::STATE_PAYMENT_REFUSED:
                    $task->setStatus($task::STATE_PAYMENT_REFUSED);
                    $this->processingHandler->saveProcessing($subTask, true);
                    break;

                case $subTask::STATE_ORDER_NOT_FOUND:
                    $task->setStatus($task::STATE_INCONSISTENT_CONTEXT);
                    $this->processingHandler->saveProcessing($subTask, false);
                    break;

                default:
                    $task->setStatus($task::STATE_FATAL_ERROR);
                    $this->processingHandler->saveProcessing($subTask, false);
            }

            if (PAYGREEN_ENV === 'DEV') {
                /** @var TestingPaymentHandler $testingPaymentHandler */
                $testingPaymentHandler = $this->getService('handler.payment_testing');

                $testingPaymentHandler->manageFakeOrder($task, $subTask);
            }
        }
    }
}
