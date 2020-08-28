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

/**
 * Interface PGDomainInterfacesEntitiesButtonInterface
 * @package PGDomain\Interfaces\Entities
 */
interface PGDomainInterfacesEntitiesButtonInterface extends PGFrameworkInterfacesPersistedEntityInterface
{
    /**
     * @return int
     */
    public function id();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getImageSrc();

    /**
     * @return int
     */
    public function getImageHeight();

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @return int
     */
    public function getMinAmount();

    /**
     * @return int
     */
    public function getMaxAmount();

    /**
     * @return string
     */
    public function getIntegration();

    /**
     * @return string
     */
    public function getDisplayType();

    /**
     * @return string
     */
    public function getPaymentMode();

    /**
     * @return string
     */
    public function getPaymentType();

    /**
     * @return int
     */
    public function getPaymentNumber();

    /**
     * @return string
     */
    public function getPaymentReport();

    /**
     * @return bool
     */
    public function isOrderRepeated();

    /**
     * @return int
     */
    public function getFirstPaymentPart();

    /**
     * @return mixed
     */
    public function getDiscount();

    /**
     * @param string $label
     * @return self
     */
    public function setLabel($label);

    /**
     * @param string $image
     * @return self
     */
    public function setImageSrc($image);

    /**
     * @param int $height
     * @return self
     */
    public function setImageHeight($height);

    /**
     * @param int $position
     * @return self
     */
    public function setPosition($position);

    /**
     * @param string $displayType
     * @return self
     */
    public function setDisplayType($displayType);

    /**
     * @param string $integration
     * @return self
     */
    public function setIntegration($integration);

    /**
     * @param int $maxAmount
     * @return self
     */
    public function setMaxAmount($maxAmount);

    /**
     * @param int $minAmount
     * @return self
     */
    public function setMinAmount($minAmount);

    /**
     * @param string $paymentMode
     * @return self
     */
    public function setPaymentMode($paymentMode);

    /**
     * @param string $paymentType
     * @return self
     */
    public function setPaymentType($paymentType);

    /**
     * @param int $firstPaymentPart
     * @return self
     */
    public function setFirstPaymentPart($firstPaymentPart);

    /**
     * @param int $paymentNumber
     * @return self
     */
    public function setPaymentNumber($paymentNumber);

    /**
     * @param string $paymentReport
     * @return self
     */
    public function setPaymentReport($paymentReport);

    /**
     * @param string $discount
     * @return self
     */
    public function setDiscount($discount);

    /**
     * @param bool $isOrderRepeated
     * @return self
     */
    public function setOrderRepeated($isOrderRepeated);
}
