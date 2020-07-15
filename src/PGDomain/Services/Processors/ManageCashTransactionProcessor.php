<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.0
 */

/**
 * Class PGDomainServicesProcessorsManageCashTransactionProcessor
 * @package PGDomain\Services\Processors
 */
class PGDomainServicesProcessorsManageCashTransactionProcessor extends PGDomainFoundationsProcessorsAbstractTransactionManagementProcessor
{
    const PROCESSOR_NAME = 'CashTransaction';

    protected function defaultStep(PGDomainTasksTransactionManagementTask $task)
    {
        switch ($task->getTransaction()->getResult()->getStatus()) {
            case PGDomainServicesPaygreenFacade::STATUS_REFUSED:
                $this->pushStep('refusedPayment');
                break;

            case PGDomainServicesPaygreenFacade::STATUS_SUCCESSED:
                $this->pushSteps(array(
                    array('setOrderStatus', array('VALIDATE')),
                    'checkTestingMode',
                    'checkAmountValidity',
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
}
