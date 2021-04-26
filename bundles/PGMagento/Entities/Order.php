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
 * @version   2.0.0
 *
 */

use Magento\Quote\Model\QuoteFactory;
use Magento\Sales\Model\Order;

/**
 * Class PGMagentoEntitiesOrder
 *
 * @package PGMagento\Entities
 * @method Order getLocalEntity()
 */
class PGMagentoEntitiesOrder extends PGDatabaseFoundationsEntityWrapped implements PGShopInterfacesEntitiesOrder
{
    /** @var PGShopInterfacesEntitiesCart|null */
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
        return $this->getLocalEntity()->getId();
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
        return PGShopToolsPrice::toInteger($this->getLocalEntity()->getGrandTotal());
    }

    /**
     * @inheritdoc
     */
    public function getTotalUserAmount()
    {
        return PGShopToolsPrice::fixFloat($this->getLocalEntity()->getGrandTotal());
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
        return new PGMagentoEntitiesCustomer($this->getLocalEntity());
    }

    /**
     * @inheritdoc
     */
    public function getBillingAddress()
    {
        $localBillingAddress = $this->getLocalEntity()->getBillingAddress();

        return $localBillingAddress
            ? new PGMagentoEntitiesAddress($localBillingAddress)
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
        /** @var PGShopServicesOrderStateMapper $orderStateMapper */
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
        /** @var QuoteFactory $quoteFactory */
        $quoteFactory = $this->getService('magento')->get('Magento\Quote\Model\QuoteFactory');

        $quote_id = $this->getLocalEntity()->getQuoteId();

        if (!$quote_id) {
            throw new Exception('Cart not found.');
        }

        $localCart = $quoteFactory->create()->load($quote_id);

        $this->cart = new PGMagentoEntitiesCart($localCart);
    }
}
