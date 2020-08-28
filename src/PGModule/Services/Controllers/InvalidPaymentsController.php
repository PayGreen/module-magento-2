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
 * @version   1.0.1
 */

use Magento\Sales\Model\Order;

class PGModuleServicesControllersInvalidPaymentsController extends PGServerFoundationsAbstractController
{
    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws PGDomainExceptionsUnauthorizedOrderTransitionException
     * @throws PGDomainExceptionsUnnecessaryOrderTransitionException
     * @throws Exception
     */
    public function abortPaymentAction()
    {
        /** @var PGServerServicesLinker $linker */
        $linker = $this->getService('linker');

        /** @var PGDomainServicesManagersOrderManager $orderManager */
        $orderManager = $this->getService('manager.order');

        $return = 'order.history';

        /** @var PGDomainInterfacesEntitiesOrderInterface|null $order */
        $order = $this->getCurrentOrder();

        $orderManager->updateOrder($order, 'CANCEL', 'CASH');

        if ($this->rebuildCart($order)) {
            $return = 'checkout';
        }

        $url = $linker->buildLocalUrl($return);

        return $this->redirect($url);
    }

    /**
     * @return PGServerComponentsResponsesForwardResponse
     * @throws PGDomainExceptionsUnauthorizedOrderTransitionException
     * @throws PGDomainExceptionsUnnecessaryOrderTransitionException
     * @throws Exception
     */
    public function refusePaymentAction()
    {
        /** @var PGServerServicesLinker $linker */
        $linker = $this->getService('linker');

        $return = 'order.history';

        /** @var PGDomainInterfacesEntitiesOrderInterface|null $order */
        $order = $this->getCurrentOrder();

        if ($this->rebuildCart($order)) {
            $return = 'checkout';
        }

        return $this->forward('displayNotification@front.notification', array(
            'title' => 'frontoffice.payment.results.payment.refused.title',
            'message' => '~notice_payment_refused',
            'url' => array(
                'link' => $linker->buildLocalUrl($return),
                'text' => 'frontoffice.payment.results.payment.refused.link',
                'reload' => true,
            )
        ));

    }

    /**
     * @return PGDomainInterfacesEntitiesOrderInterface
     * @throws Exception
     */
    protected function getCurrentOrder()
    {
        /** @var PGDomainServicesManagersOrderManager $orderManager */
        $orderManager = $this->getService('manager.order');

        /** @var Order $localOrder */
        $localOrder = $this->getService('magento')->get('Magento\Checkout\Model\Session')->getLastRealOrder();

        $order = $orderManager->getByReference($localOrder->getIncrementId());

        if ($order === null) {
            throw new Exception("Unable to retrieve current order.");
        }

        return $order;
    }

    /**
     * @param PGDomainInterfacesEntitiesOrderInterface $order
     * @return bool
     * @throws Exception
     * @todo Implements user_cancel_behavior.
     */
    protected function rebuildCart(PGDomainInterfacesEntitiesOrderInterface $order)
    {
        /** @var PGFrameworkServicesSettings $settings */
        $settings = $this->getService('settings');

        /** @var PGModuleServicesHandlersCartHandler $cartHandler */
        $cartHandler = $this->getService('handler.cart');

        if (true || $settings->get('user_cancel_behavior')) {
            if ($cartHandler->rebuildCart($order)) {
                return true;
            } else {
                $this->getLogger()->error("Unable to rebuild cart.");
            }
        }

        return false;
    }
}
