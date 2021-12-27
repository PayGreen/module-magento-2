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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGPayment\Services\Processors;

use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGPayment\Components\Tasks\TransactionManagement as TransactionManagementTaskComponent;
use PGI\Module\PGPayment\Foundations\Processors\AbstractTransactionManagementProcessor;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use PGI\Module\PGPayment\Services\Managers\TransactionManager;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use Exception;

/**
 * Class ManageTokenizeTransactionProcessor
 * @package PGPayment\Services\Processors
 */
class ManageTokenizeTransactionProcessor extends AbstractTransactionManagementProcessor
{
    const PROCESSOR_NAME = 'TokenizeTransaction';

    protected function defaultStep(TransactionManagementTaskComponent $task)
    {
        /** @var OrderEntityInterface|null $order */
        $order = $this->officer->getOrder($task->getProvisioner());

        $task->setOrder($order);

        switch ($task->getTransaction()->getResult()->getStatus()) {
            case PaygreenFacade::STATUS_REFUSED:
            case PaygreenFacade::STATUS_CANCELLING:
                $this->pushStep('refusedPayment');
                break;

            case PaygreenFacade::STATUS_PENDING_EXEC:
                $this->pushSteps(array(
                    array('setOrderStatus', array('AUTH')),
                    'saveOrder',
                    'insertTransaction',
                    array('setStatus', array(
                        $task::STATE_SUCCESS
                    ))
                ));

                break;

            case PaygreenFacade::STATUS_SUCCESSED:
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

    protected function updateTransactionStep(TransactionManagementTaskComponent $task)
    {
        /** @var Logger $logger */
        $logger = $this->getService('logger');

        /** @var TransactionManager $transactionManager */
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
