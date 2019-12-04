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

namespace Paygreen\Payment\Controller\Payment;

use Paygreen\Payment\Foundations\AbstractActionFront;
use PGFrameworkServicesLogger;
use PGDomainTasksPaymentValidationTask;

class Notify extends AbstractActionFront
{
    public function execute()
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $pid = isset($_GET['pid']) ? $_GET['pid'] : null;

        $logger->info("IPN for PID : '$pid'.");

        $task = new PGDomainTasksPaymentValidationTask($pid);

        $processor = $this->getService('processor.payment_validation');

        $processor->execute($task);

        switch ($task->getStatus()) {
            case $task::STATE_SUCCESS:
            case $task::STATE_PAYMENT_REFUSED:
            case $task::STATE_PAYMENT_ABORTED:
                die('Notification success. Final state : ' . $task->getStatusName($task->getStatus()));

            case $task::STATE_PID_LOCKED:
            case $task::STATE_INCONSISTENT_CONTEXT:
            case $task::STATE_FATAL_ERROR:
            case $task::STATE_WORKFLOW_ERROR:
            case $task::STATE_PAYGREEN_UNAVAILABLE:
            default:
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                die('Notification failure. Final state : ' . $task->getStatusName($task->getStatus()));
        }
    }
}
