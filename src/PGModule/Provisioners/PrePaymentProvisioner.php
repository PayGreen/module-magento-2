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
 * @version   1.2.4
 *
 */

use Magento\Framework\App\ObjectManager;
use Magento\Checkout\Model\Session;
use Magento\Sales\Model\Order;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class PGModuleProvisionersPrePaymentProvisioner
 * @package PGModule\Provisioners
 */
class PGModuleProvisionersPrePaymentProvisioner extends PGFrameworkFoundationsAbstractObject implements PGDomainInterfacesPrePaymentProvisionerInterface
{
    /** @var CustomerInterface */
    private $customer;

    /** @var AddressInterface */
    private $address;

    /** @var Order */
    private $order;

    /**
     * PGModuleProvisionersPrePaymentProvisioner constructor.
     * @param ObjectManager $objectManager
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function __construct(ObjectManager $objectManager)
    {
        /** @var Session $session */
        $session = $objectManager->get('Magento\Checkout\Model\Session');

        $this->order = $session->getLastRealOrder();
        $this->customer = $session->getQuote()->getCustomer();
        $this->address = $this->order->getBillingAddress();

        if ($this->address === null) {
            throw new Exception("Billing address not found.");
        }
    }

    public function getReference()
    {
        $suffix = (PAYGREEN_ENV === 'DEV') ? '-' . mt_rand(10000, 99999) : '';

        return $this->order->getId() . $suffix;
    }

    public function getCurrency()
    {
        return $this->order->getOrderCurrencyCode();
    }

    public function getTotalAmount()
    {
        $price = $this->order->getGrandTotal();

        return PGDomainToolsPrice::toInteger($price);
    }

    public function getShippingAmount()
    {
        $price = $this->order->getShippingAmount();

        return PGDomainToolsPrice::toInteger($price);
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
     * @return PGDomainInterfacesEntitiesCartItemInterface[]
     */
    public function getItems()
    {
        $items = array();

        foreach ($this->order->getItems() as $item) {
            $items[] = new PGModuleEntitiesCartItem($item);
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
        /** @var Magento\Framework\DataObject $carrier */
        $carrier = $this->order->getShippingMethod(true);

        return $carrier->getData('method');
    }

    public function getShippingWeight()
    {
        return$this->order->getWeight();
    }
}
