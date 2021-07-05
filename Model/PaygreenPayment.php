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
 * @version   2.1.0
 *
 */

namespace Paygreen\Payment\Model;

use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Payment\Model\InfoInterface;
use PGSystemServicesContainer;
use PGMagentoEntitiesOrder;
use PGModuleServicesHandlersBehavior;
use PGPaymentComponentsEventsRefundEvent;
use PGModuleServicesBroadcaster;
use PGShopInterfacesProvisionersCheckout;
use PGMagentoProvisionersCheckoutProvisioner;
use PGPaymentServicesHandlersCheckoutHandler;
use PGModuleInterfacesModuleFacade;

require_once PAYGREEN_BOOTSTRAP_SRC;

class PaygreenPayment extends AbstractMethod
{
    public $_code = 'paygreenpayment';

    protected $_isInitializeNeeded = false;

    protected $_canOrder = false;
    protected $_canAuthorize = true;
    protected $_canCapture = false;
    protected $_canCapturePartial = false;
    protected $_canCaptureOnce = false;
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = true;
    protected $_canVoid = false;
    protected $_canUseInternal = false;
    protected $_canUseCheckout = true;

    protected function getService ($name)
    {
        return PGSystemServicesContainer::getInstance()->get($name);
    }

    public function getTitle()
    {
        return $this->getService('handler.translation')->translate('payment_bloc');
    }

    public function isActive($storeId = null)
    {
        /** @var PGModuleInterfacesModuleFacade $moduleFacade */
        $moduleFacade = $this->getService('facade.module');

        return parent::isActive($storeId) && $moduleFacade->isActive();
    }

    public function canRefund()
    {
        /** @var PGModuleServicesHandlersBehavior $behaviorHandler */
        $behaviorHandler = $this->getService('handler.behavior');

        $canRefund = parent::canRefund();

        return ($canRefund && $behaviorHandler->get('transmission_on_refund'));
    }

    public function refund(InfoInterface $payment, $amount)
    {
        $this->getService('logger')->debug("PaygreenPayment::refund", $amount);

        /** @var PGModuleServicesBroadcaster $broadcaster */
        $broadcaster = $this->getService('broadcaster');

        $order = new PGMagentoEntitiesOrder($payment->getOrder());

        $event = new PGPaymentComponentsEventsRefundEvent($order, $amount);

        $broadcaster->fire($event);

        return $this;
    }

    public function authorize(InfoInterface $payment, $amount)
    {
        $this->getService('logger')->debug("PaygreenPayment::authorize", $amount);

        $payment->setTransactionId(time());
        $payment->setIsTransactionClosed(false);
        $payment->setIsTransactionPending(true);

        return $this;
    }

    public function getConfigPaymentAction()
    {
        return 'authorize';
    }

    public function capture(InfoInterface $payment, $amount)
    {
        $this->getService('logger')->debug("PaygreenPayment::capture", $amount);

        return $this;
    }

    public function canUseCheckout()
    {
        /** @var PGPaymentServicesHandlersCheckoutHandler $checkoutHandler */
        $checkoutHandler = $this->getService('handler.checkout');

        /** @var PGShopInterfacesProvisionersCheckout $checkoutProvisioner */
        $checkoutProvisioner = new PGMagentoProvisionersCheckoutProvisioner();

        return $checkoutHandler->isCheckoutAvailable($checkoutProvisioner);
    }
}
