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
 * @version   2.0.2
 *
 */

/**
 * Interface PGShopInterfacesEntitiesOrder
 * @package PGShop\Interfaces\Entities
 */
interface PGShopInterfacesEntitiesOrder extends PGShopInterfacesShopable
{
    /**
     * @return mixed
     */
    public function id();

    /**
     * @return string
     */
    public function getReference();

    /**
     * @return int
     */
    public function getTotalAmount();

    /**
     * @return float
     */
    public function getTotalUserAmount();

    /**
     * @return string
     */
    public function getState();

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @return PGShopInterfacesEntitiesCustomer
     */
    public function getCustomer();

    /**
     * @return PGShopInterfacesEntitiesAddress|null
     */
    public function getBillingAddress();

    /**
     * @return string
     */
    public function getCustomerMail();

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * @return float
     */
    public function getShippingWeight();

    /**
     * @return PGShopInterfacesEntitiesShopableItem[]
     */
    public function getItems();
}
