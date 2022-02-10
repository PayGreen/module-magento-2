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

namespace PGI\Module\PGMagento\Components\Provisioners;

use Magento\Framework\App\ObjectManager as LocalObjectManager;
use Magento\Checkout\Model\Session as LocalSession;
use Magento\Framework\DataObject as LocalDataObject;
use Magento\Quote\Model\QuoteFactory as LocalQuoteFactory;
use Magento\Sales\Model\Order as LocalOrder;
use Magento\Customer\Api\Data\CustomerInterface as LocalCustomerInterface;
use Magento\Customer\Api\Data\AddressInterface as LocalAddressInterface;
use Magento\Framework\Exception\LocalizedException as LocalLocalizedException;
use Magento\Framework\Exception\NoSuchEntityException as LocalNoSuchEntityException;
use PGI\Module\PGMagento\Entities\Address;
use PGI\Module\PGMagento\Entities\Carrier;
use PGI\Module\PGMagento\Entities\Cart;
use PGI\Module\PGMagento\Entities\CartItem;
use PGI\Module\PGMagento\Entities\Customer;
use PGI\Module\PGShop\Interfaces\Entities\CartEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ShopableItemEntityInterface;
use PGI\Module\PGShop\Interfaces\Provisioners\PrePaymentProvisionerInterface;
use PGI\Module\PGShop\Tools\Price as PriceTool;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class PrePayment
 * @package PGMagento\Components\Provisioners
 */
class PrePayment extends AbstractObject implements PrePaymentProvisionerInterface
{
    /** @var LocalObjectManager */
    private $objectManager;

    /** @var LocalCustomerInterface */
    private $customer;

    /** @var LocalAddressInterface */
    private $address;

    /** @var LocalOrder */
    private $order;

    /** @var CartEntityInterface */
    private $cart;

    /**
     * PrePayment constructor.
     * @param LocalObjectManager $objectManager
     * @throws LocalLocalizedException
     * @throws LocalNoSuchEntityException
     */
    public function __construct(LocalObjectManager $objectManager)
    {
        /** @var LocalSession $session */
        $session = $objectManager->get('Magento\Checkout\Model\Session');

        $this->objectManager = $objectManager;
        $this->order = $session->getLastRealOrder();
        $this->loadCart();
        $this->customer = $session->getQuote()->getCustomer();
        $this->address = $this->order->getBillingAddress();

        if ($this->address === null) {
            throw new Exception("Billing address not found.");
        }
    }

    public function getReference()
    {
        return $this->order->getId();
    }

    public function getCurrency()
    {
        return $this->order->getOrderCurrencyCode();
    }

    public function getTotalAmount()
    {
        $price = $this->order->getGrandTotal();

        return PriceTool::toInteger($price);
    }

    public function getShippingAmount()
    {
        $price = $this->order->getShippingAmount();

        return PriceTool::toInteger($price);
    }

    public function getMail()
    {
        $result = null;

        if ($this->customer->getId()) {
            $result = $this->customer->getEmail();
        } else {
            $result = $this->order->getCustomerEmail();
        }

        return $result;
    }

    public function getCountry()
    {
        return $this->address->getCountryId();
    }

    public function getAddressLineOne()
    {
        $street = $this->address->getStreet();

        return (is_array($street) && isset($street[0])) ? $street[0] : '';
    }

    public function getAddressLineTwo()
    {
        $street = $this->address->getStreet();

        return (is_array($street) && isset($street[1])) ? $street[1] : '';
    }

    public function getCity()
    {
        return $this->address->getCity();
    }

    public function getZipCode()
    {
        return $this->address->getPostcode();
    }

    public function getCustomerId()
    {
        return $this->customer->getId();
    }

    public function getFirstName()
    {
        $result = null;

        if ($this->customer->getId()) {
            $result = $this->customer->getFirstname();
        } else {
            $result = $this->order->getCustomerFirstname();
        }

        return $result;
    }

    public function getLastName()
    {
        $result = null;

        if ($this->customer->getId()) {
            $result = $this->customer->getLastname();
        } else {
            $result = $this->order->getCustomerLastname();
        }

        return $result;
    }

    /**
     * @return ShopableItemEntityInterface[]
     */
    public function getItems()
    {
        $items = array();

        foreach ($this->order->getItems() as $item) {
            $items[] = new CartItem($item);
        }

        return $items;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata()
    {
        return array(
            'order_id' => $this->order->getId()
        );
    }

    public function getShippingName()
    {
        /** @var LocalDataObject $carrier */
        $carrier = $this->order->getShippingMethod(true);

        return $carrier->getData('method');
    }

    public function getShippingWeight()
    {
        return $this->order->getWeight();
    }

    /**
     * @inheritDoc
     */
    public function getCustomer()
    {
        return new Customer($this->customer);
    }

    /**
     * @inheritDoc
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @throws Exception
     */
    protected function loadCart()
    {
        /** @var LocalQuoteFactory $quoteFactory */
        $quoteFactory = $this->getService('magento')->get('Magento\Quote\Model\QuoteFactory');

        $quote_id = $this->order->getQuoteId();

        if (!$quote_id) {
            throw new Exception('Cart not found.');
        }

        $localCart = $quoteFactory->create()->load($quote_id);

        $this->cart = new Cart($localCart);
    }

    /**
     * @inheritDoc
     */
    public function getCarrier()
    {
        $shippingMethod = $this->order->getShippingMethod();

        return new Carrier($shippingMethod);
    }

    /**
     * @inheritDoc
     */
    public function getShippingAddress()
    {
        $localShippingAddress = $this->order->getShippingAddress();

        if ($localShippingAddress === null) {
            throw new Exception('Shipping address data missing.');
        }

        return new Address($localShippingAddress);
    }

    /**
     * @inheritDoc
     */
    public function getBillingAddress()
    {
        $localBillingAddress = $this->order->getBillingAddress();

        if ($localBillingAddress === null) {
            throw new Exception('Billing address data missing.');
        }

        return new Address($localBillingAddress);
    }
}
