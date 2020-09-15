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
 * @version   1.1.0
 */

/**
 * Interface PGDomainInterfacesPrePaymentProvisionerInterface
 * @package PGDomain\Interfaces
 */
interface PGDomainInterfacesPrePaymentProvisionerInterface
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
     * @return PGDomainInterfacesEntitiesCartItemInterface[]
     */
    public function getItems();

    /**
     * @return array
     */
    public function getMetadata();
}
