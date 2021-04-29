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
 * @version   2.0.1
 *
 */

use Magento\Sales\Model\Order;
use Magento\Framework\App\ObjectManager;

class PGMagentoPaymentServicesControllersInvalidPaymentsController extends PGServerFoundationsAbstractController
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var PGShopServicesManagersOrder */
    private $orderManager;

    /** @var PGMagentoServicesHandlersCartHandler */
    private $cartHandler;

    public function __construct(
        ObjectManager $objectManager,
        PGShopServicesManagersOrder $orderManager,
        PGMagentoServicesHandlersCartHandler $cartHandler
    )
    {
        $this->objectManager = $objectManager;
        $this->orderManager = $orderManager;
        $this->cartHandler = $cartHandler;
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws PGShopExceptionsUnauthorizedOrderTransition
     * @throws PGShopExceptionsUnnecessaryOrderTransition
     * @throws Exception
     */
    public function abortPaymentAction()
    {
        $return = 'order.history';

        /** @var PGShopInterfacesEntitiesOrder|null $order */
        $order = $this->getCurrentOrder();

        $this->orderManager->updateOrder($order, 'CANCEL', 'CASH');

        if ($this->getSettings()->get('regenerate_cart_on_cancelled_payment')) {
            $return = ($this->rebuildCart($order)) ? 'checkout' : $return;
        }

        $url = $this->getLinker()->buildLocalUrl($return);

        return $this->redirect($url);
    }

    /**
     * @return PGServerComponentsResponsesForwardResponse
     * @throws PGShopExceptionsUnauthorizedOrderTransition
     * @throws PGShopExceptionsUnnecessaryOrderTransition
     * @throws Exception
     */
    public function refusePaymentAction()
    {
        $return = 'order.history';
        $result = 'refused_without_regenerate_cart';
        $details = "frontoffice.payment.results.payment.{$result}.details";

        /** @var PGShopInterfacesEntitiesOrder|null $order */
        $order = $this->getCurrentOrder();

        $this->orderManager->updateOrder($order, 'CANCEL', 'CASH');

        if ($this->getSettings()->get('regenerate_cart_on_refused_payment')) {
            if ($this->rebuildCart($order)) {
                $return = 'checkout';
                $result = 'refused_with_regenerate_cart';
                $details = '';
            }
        }

        return $this->forward('displayNotification@front.notification', array(
            "title" => "frontoffice.payment.results.payment.{$result}.title",
            "message" => "~message_payment_refused",
            "details" => $details,
            "url" => array(
                "link" => $this->getLinker()->buildLocalUrl($return),
                "text" => "frontoffice.payment.results.payment.{$result}.link",
                'reload' => true,
            )
        ));
    }

    /**
     * @return PGShopInterfacesEntitiesOrder
     * @throws Exception
     */
    protected function getCurrentOrder()
    {
        /** @var Order $localOrder */
        $localOrder = $this->objectManager->get('Magento\Checkout\Model\Session')->getLastRealOrder();

        $order = $this->orderManager->getByReference($localOrder->getIncrementId());

        if ($order === null) {
            throw new Exception("Unable to retrieve current order.");
        }

        return $order;
    }

    /**
     * @param PGShopInterfacesEntitiesOrder $order
     * @return bool
     * @throws Exception
     * @todo Implements user_cancel_behavior.
     */
    protected function rebuildCart(PGShopInterfacesEntitiesOrder $order)
    {
        if (true || $this->getSettings()->get('user_cancel_behavior')) {
            if ($this->cartHandler->rebuildCart($order)) {
                return true;
            } else {
                $this->getLogger()->error("Unable to rebuild cart.");
            }
        }

        return false;
    }
}
