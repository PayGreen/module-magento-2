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
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGShop\Interfaces\Provisioners;

use PGI\Module\PGShop\Interfaces\Entities\AddressEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\CarrierEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\CartEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\CustomerEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ShopableItemEntityInterface;
use Exception;

/**
 * Interface PrePaymentProvisionerInterface
 * @package PGShop\Interfaces\Provisioners
 */
interface PrePaymentProvisionerInterface
{
    /**
     * @return string
     */
    public function getReference();

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * @return int
     */
    public function getTotalAmount();

    /**
     * @return int
     */
    public function getShippingAmount();

    /**
     * @return int
     */
    public function getShippingName();

    /**
     * @return float
     */
    public function getShippingWeight();

    /**
     * @return string
     */
    public function getMail();

    /**
     * @return string
     */
    public function getCountry();

    /**
     * @return string
     */
    public function getAddressLineOne();

    /**
     * @return string
     */
    public function getAddressLineTwo();

    /**
     * @return string
     */
    public function getCity();

    /**
     * @return string
     */
    public function getZipCode();

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @return ShopableItemEntityInterface[]
     */
    public function getItems();

    /**
     * @return array
     */
    public function getMetadata();

    /**
     * @return CartEntityInterface
     */
    public function getCart();

    /**
     * @return CustomerEntityInterface
     */
    public function getCustomer();

    /**
     * @return CarrierEntityInterface
     */
    public function getCarrier();

    /**
     * @return AddressEntityInterface
     * @throws Exception
     */
    public function getShippingAddress();

    /**
     * @return AddressEntityInterface
     * @throws Exception
     */
    public function getBillingAddress();
}
