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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGMagento\Entities;

use Magento\Quote\Model\QuoteFactory as LocalQuoteFactory;
use Magento\Sales\Model\Order as Localorder;
use PGI\Module\PGDatabase\Foundations\AbstractEntityWrapped;
use PGI\Module\PGMagento\Entities\Address;
use PGI\Module\PGMagento\Entities\Carrier;
use PGI\Module\PGMagento\Entities\Cart;
use PGI\Module\PGMagento\Entities\Customer;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGShop\Interfaces\Entities\CartEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Services\Mappers\OrderStateMapper;
use PGI\Module\PGShop\Tools\Price as PriceTool;
use Exception;

/**
 * Class Order
 *
 * @package PGMagento\Entities
 * @method LocalOrder getLocalEntity()
 */
class Order extends AbstractEntityWrapped implements OrderEntityInterface
{
    /** @var CartEntityInterface|null */
    private $cart = null;

    public function __construct($localEntity)
    {
        parent::__construct($localEntity);

        $this->loadCart();
    }

    /**
     * @inheritdoc
     */
    public function id()
    {
        return (int) $this->getLocalEntity()->getId();
    }

    /**
     * @inheritdoc
     */
    public function getReference()
    {
        return $this->getLocalEntity()->getIncrementId();
    }

    /**
     * @inheritdoc
     */
    public function getTotalAmount()
    {
        return PriceTool::toInteger($this->getLocalEntity()->getGrandTotal());
    }

    /**
     * @inheritdoc
     */
    public function getTotalUserAmount()
    {
        return PriceTool::fixFloat($this->getLocalEntity()->getGrandTotal());
    }

    /**
     * @inheritdoc
     */
    public function getCustomerId()
    {
        return $this->getLocalEntity()->getCustomerId();
    }

    /**
     * @inheritdoc
     */
    public function getCustomer()
    {
        return new Customer($this->getLocalEntity());
    }

    /**
     * @inheritdoc
     */
    public function getBillingAddress()
    {
        $localBillingAddress = $this->getLocalEntity()->getBillingAddress();

        return $localBillingAddress
            ? new Address($localBillingAddress)
            : null;
    }

    public function getCustomerMail()
    {
        return $this->getLocalEntity()->getCustomerEmail();
    }

    public function getCurrency()
    {
        return $this->getLocalEntity()->getBaseCurrencyCode();
    }

    public function getState()
    {
        /** @var OrderStateMapper $orderStateMapper */
        $orderStateMapper = $this->getService('mapper.order_state');

        return $orderStateMapper->getOrderState(array(
            'state' => $this->getLocalEntity()->getState(),
            'status' => $this->getLocalEntity()->getStatus()
        ));
    }

    public function getShippingWeight()
    {
        return $this->cart->getShippingWeight();
    }

    public function getItems()
    {
        return $this->cart->getItems();
    }

    /**
     * @throws Exception
     */
    protected function loadCart()
    {
        /** @var LocalQuoteFactory $quoteFactory */
        $quoteFactory = $this->getService('magento')->get('Magento\Quote\Model\QuoteFactory');

        $quote_id = $this->getLocalEntity()->getQuoteId();

        if (!$quote_id) {
            throw new Exception('Cart not found.');
        }

        $localCart = $quoteFactory->create()->load($quote_id);

        $this->cart = new Cart($localCart);
    }

    /**
     * @inheritdoc
     */
    public function getShippingAddress()
    {
        $shippingAddress = $this->getLocalEntity()->getShippingAddress();

        return new Address($shippingAddress);
    }

    /**
     * @inheritdoc
     */
    public function getCarrier()
    {
         /** @var Logger $logger */
        $logger = $this->getService('logger');

        $carrierName = $this->getLocalEntity()->getShippingMethod();

        if (!$carrierName) {
            $logger->warning('Local carrier name not found in Order entity.');

            return null;
        } else {
            return new Carrier($carrierName);
        }
    }

    /**
     * @inheritdoc
     */
    public function paidWithPaygreen()
    {
        $result = false;

        $payment = $this->getLocalEntity()->getPayment();

        if ($payment !== null) {
            $paymentMethod = $payment->getMethod();

            $result = (strtolower($paymentMethod) === 'paygreenpayment');
        }

        return $result;
    }
}
