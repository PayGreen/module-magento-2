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
 * @version   0.3.4
 */

/**
 * Class PGDomainServicesProcessorsPaymentValidationProcessor
 * @package PGDomain\Services\Processors
 */
class PGDomainServicesProcessorsPaymentValidationProcessor extends PGFrameworkFoundationsAbstractProcessor
{
    const PROCESSOR_NAME = 'PaymentValidation';

    /** @var PGDomainInterfacesOfficersPostPaymentOfficerInterface */
    protected $officer;

    public function __construct()
    {
        $this->setSteps(array(
            'verifyPIDValidity',
            'verifyModuleActivation',
            'putLock',
            'paygreenCall',
            'manageAbortedTransaction',
            'buildProvisioner',
            'switchPaymentMode'
        ));
    }

    /**
     * @param PGDomainInterfacesOfficersPostPaymentOfficerInterface $officer
     */
    public function setPostPaymentOfficer($officer)
    {
        $this->officer = $officer;
    }

    protected function verifyPIDValidityStep(PGDomainTasksPaymentValidationTask $task)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        if (!$task->getPid()) {
            $logger->error('PID not found.');
            $task->setStatus($task::STATE_PID_NOT_FOUND);
        }
    }

    protected function verifyModuleActivationStep(PGDomainTasksPaymentValidationTask $task)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGFrameworkInterfacesModuleFacadeInterface $moduleFacade */
        $moduleFacade = $this->getService('facade.module');

        if (!$moduleFacade->isActive()) {
            $logger->error('Paygreen module is deactivated.');
            $task->setStatus($task::STATE_INCONSISTENT_CONTEXT);
        }
    }

    /**
     * @param PGDomainTasksPaymentValidationTask $task
     * @throws Exception
     */
    protected function putLockStep(PGDomainTasksPaymentValidationTask $task)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGDomainServicesManagersLockManager $lockManager */
        $lockManager = $this->getService('manager.lock');

        /** @var PGFrameworkServicesHandlersBehaviorHandler $behaviorHandler */
        $behaviorHandler = $this->getService('handler.behavior');

        $useTransactionLock = $behaviorHandler->get('use_transaction_lock');

        if ($useTransactionLock && $lockManager->isLocked($task->getPid())) {
            $logger->error("Payment validation - PID locked : '{$task->getPid()}'.");
            $task->setStatus($task::STATE_PID_LOCKED);
        }
    }

    protected function paygreenCallStep(PGDomainTasksPaymentValidationTask $task)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGClientServicesApiFacade $apiFacade */
        $apiFacade = $this->getService('paygreen.facade')->getApiFacade();

        try {
            /** @var PGClientEntitiesPaygreenTransaction $transaction */
            $transaction = $apiFacade->getTransactionInfo($task->getPid());

            $task->setTransaction($transaction);
        } catch (Exception $exception) {
            $logger->error("Paygreen API error : {$exception->getMessage()}", $exception);

            $this->addException($exception);

            $task->setStatus($task::STATE_PAYGREEN_UNAVAILABLE);
        }
    }

    protected function manageAbortedTransactionStep(PGDomainTasksPaymentValidationTask $task)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        if ($task->getTransaction()->getResult()->getStatus() === PGDomainServicesPaygreenFacade::STATUS_PENDING) {
            $logger->error('Transaction cancelled by user.');
            $task->setStatus($task::STATE_PAYMENT_ABORTED);
        }
    }

    protected function buildProvisionerStep(PGDomainTasksPaymentValidationTask $task)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        try {
            $provisioner = $this->officer->buildPostPaymentProvisioner($task->getPid(), $task->getTransaction());

            $task->setProvisioner($provisioner);
        } catch (Exception $exception) {
            $logger->error('Error during provisioner construction : ' . $exception->getMessage(), $exception);
            $task->setStatus($task::STATE_PROVIDER_ERROR);
        }
    }

    /**
     * @param PGDomainTasksPaymentValidationTask $task
     * @throws Exception
     */
    protected function switchPaymentModeStep(PGDomainTasksPaymentValidationTask $task)
    {
        /** @var PGDomainTasksTransactionManagementTask $subTask */
        $subTask = new PGDomainTasksTransactionManagementTask($task->getProvisioner());

        /** @var PGFrameworkFoundationsAbstractProcessor|null $processor */
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
                    break;

                case $subTask::STATE_PAYMENT_REFUSED:
                    $task->setStatus($task::STATE_PAYMENT_REFUSED);
                    break;

                case $subTask::STATE_ORDER_NOT_FOUND:
                    $task->setStatus($task::STATE_INCONSISTENT_CONTEXT);
                    break;

                default:
                    $task->setStatus($task::STATE_FATAL_ERROR);
            }

            if (PAYGREEN_ENV === 'DEV') {
                /** @var PGDomainServicesHandlersTestingPaymentHandler $testingPaymentHandler */
                $testingPaymentHandler = $this->getService('handler.payment_testing');

                $testingPaymentHandler->manageFakeOrder($task, $subTask);
            }
        }
    }
}
