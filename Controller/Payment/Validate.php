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

namespace Paygreen\Payment\Controller\Payment;

use Magento\Sales\Model\Order;
use Paygreen\Payment\Foundations\AbstractActionFront;
use PGFrameworkServicesLogger;
use PGDomainTasksPaymentValidationTask;
use PGFrameworkFoundationsAbstractProcessor;
use PGDomainInterfacesEntitiesOrderInterface;
use PGDomainServicesManagersOrderManager;
use PGFrameworkServicesSettings;
use PGModuleServicesHandlersCartHandler;

class Validate extends AbstractActionFront
{
    public function execute()
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $pid = isset($_GET['pid']) ? $_GET['pid'] : null;

        $logger->info("Customer validation for PID : '$pid'.");

        $task = new PGDomainTasksPaymentValidationTask($pid);

        /** @var PGFrameworkFoundationsAbstractProcessor $processor */
        $processor = $this->getService('processor.payment_validation');

        $processor->execute($task);

        switch ($task->getStatus()) {
            case $task::STATE_SUCCESS:
                return $this->dispatchByOrderState($task->getOrder());

            case $task::STATE_PAYMENT_REFUSED:
                $text = $this->getService('settings')->get('payment_error_text');

                $this->messageManager->addErrorMessage($text);

            case $task::STATE_PAYMENT_ABORTED:
            case $task::STATE_PID_NOT_FOUND:
                /** @var PGDomainInterfacesEntitiesOrderInterface $order */
                $order = $this->getCurrentOrder();

                if ($order !== null) {
                    return $this->duplicateOrder($order);
                } else {
                    return $this->redirectToRoute('customer/account');
                }

            case $task::STATE_PID_LOCKED:
                /** @var PGDomainInterfacesEntitiesOrderInterface $order */
                $order = $this->getCurrentOrder();

                if ($order !== null) {
                    return $this->dispatchByOrderState($order);
                } else {
                    return $this->redirectToRoute('customer/account');
                }

            case $task::STATE_INCONSISTENT_CONTEXT:
            case $task::STATE_FATAL_ERROR:
            case $task::STATE_WORKFLOW_ERROR:
            case $task::STATE_PAYGREEN_UNAVAILABLE:
            case $task::STATE_PROVIDER_ERROR:
                return $this->displayPaygreenMessage(
                    'Mince, nous avons rencontré une erreur... :(',
                    $processor->getExceptions(),
                    array('Impossible de traiter votre paiement auprès de notre prestataire.')
                );

            default:
        }
    }

    protected function duplicateOrder(PGDomainInterfacesEntitiesOrderInterface $order)
    {
        /** @var PGFrameworkServicesSettings $settings */
        $settings = $this->getService('settings');

        /** @var PGDomainServicesManagersOrderManager $orderManager */
        $orderManager = $this->getService('manager.order');

        /** @var PGModuleServicesHandlersCartHandler $cartHandler */
        $cartHandler = $this->getService('handler.cart');

        if ($settings->get('user_cancel_behavior')) {
            $orderManager->updateOrder($order, 'CANCEL', 'CASH');

            if ($cartHandler->rebuildCart($order)) {
                return $this->redirectToRoute('checkout/onepage');
            } else {
                $this->messageManager->addErrorMessage('Impossible de reconstituer votre panier.');
                return $this->redirectToRoute('customer/account');
            }
        } else {
            $orderManager->updateOrder($order, 'CANCEL', 'CASH');

            return $this->dispatchByOrderState($order);
        }
    }

    protected function dispatchByOrderState(PGDomainInterfacesEntitiesOrderInterface $order)
    {
        switch ($order->getState()) {
            case 'VALIDATE':
            case 'TEST':
            case 'VERIFY':
            case 'AUTH':
            case 'WAIT':
                $text = $this->getService('settings')->get('payment_success_text');

                $this->messageManager->addSuccessMessage($text);

                return $this->redirectToRoute('checkout/onepage/success');

            case 'CANCEL':
                return $this->displayPaygreenMessage(
                    "Votre paiement a été annulé",
                    null,
                    null,
                    array(
                        'link' => $this->buildUrl('sales/order/view', ['order_id' => $order->id()]),
                        'text' => "Voir les détails de votre commande.",
                        'reload' => true,
                    )
                );

            case 'ERROR':
                $text = $this->getService('settings')->get('payment_error_text');

                return $this->displayPaygreenMessage(
                    $text,
                    null,
                    null,
                    array(
                        'link' => $this->buildUrl('sales/order/view', ['order_id' => $order->id()]),
                        'text' => "Voir les détails de votre commande.",
                        'reload' => true,
                    )
                );

            case 'NEW':
                return $this->redirectToRoute('customer/account');

            default:
                return $this->redirectToRoute('customer/account');
        }
    }

    /**
     * @return PGDomainInterfacesEntitiesOrderInterface|null
     */
    protected function getCurrentOrder()
    {
        /** @var PGDomainServicesManagersOrderManager $orderManager */
        $orderManager = $this->getService('manager.order');

        /** @var Order $localOrder */
        $localOrder = $this->getService('magento')->get('Magento\Checkout\Model\Session')->getLastRealOrder();

        return $orderManager->getByReference($localOrder->getIncrementId());
    }

    protected function displayPaygreenMessage(
        $title,
        array $exceptions = null,
        array $errors = null,
        array $url = null
    ) {
        $page = $this->getResultPageFactory()
            ->create()
            ->addHandle('pgfront_checkout_message')
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true)
        ;

        $block = $page->getLayout()->getBlock('checkout_message_display');

        $block
            ->setData('title', $title)
            ->setData('errors', $errors)
            ->setData('url', $url)
            ->setData('exceptions', empty($exceptions) ? null : $exceptions)
            ->addData(['cache_lifetime' => null])
            ->setCacheable(false)
        ;

        return $page;
    }
}
