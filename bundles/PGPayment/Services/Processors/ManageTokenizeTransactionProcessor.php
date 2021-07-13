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
 * @version   2.1.1
 *
 */

/**
 * Class PGPaymentServicesProcessorsManageTokenizeTransactionProcessor
 * @package PGPayment\Services\Processors
 */
class PGPaymentServicesProcessorsManageTokenizeTransactionProcessor extends PGPaymentFoundationsProcessorsAbstractTransactionManagementProcessor
{
    const PROCESSOR_NAME = 'TokenizeTransaction';

    protected function defaultStep(PGPaymentComponentsTasksTransactionManagement $task)
    {
        /** @var PGShopInterfacesEntitiesOrder|null $order */
        $order = $this->officer->getOrder($task->getProvisioner());

        $task->setOrder($order);

        switch ($task->getTransaction()->getResult()->getStatus()) {
            case PGPaymentServicesPaygreenFacade::STATUS_REFUSED:
            case PGPaymentServicesPaygreenFacade::STATUS_CANCELLING:
                $this->pushStep('refusedPayment');
                break;

            case PGPaymentServicesPaygreenFacade::STATUS_PENDING_EXEC:
                $this->pushSteps(array(
                    array('setOrderStatus', array('AUTH')),
                    'saveOrder',
                    'insertTransaction',
                    array('setStatus', array(
                        $task::STATE_SUCCESS
                    ))
                ));

                break;

            case PGPaymentServicesPaygreenFacade::STATUS_SUCCESSED:
                $this->pushSteps(array(
                    array('setOrderStatus', array('VALIDATE')),
                    'checkTestingMode',
                    'checkAmountValidity',
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

    protected function updateTransactionStep(PGPaymentComponentsTasksTransactionManagement $task)
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGPaymentServicesManagersTransactionManager $transactionManager */
        $transactionManager = $this->getService('manager.transaction');

        try {
            $transactionManager->updateTransaction(
                $task->getPid(),
                $task->getOrderStateTo()
            );
        } catch (Exception $exception) {
            $this->addException($exception);
            $logger->error('Error on update transaction : ' . $exception->getMessage(), $exception);
        }
    }
}
