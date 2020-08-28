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
 * Class PGDomainEntitiesButton
 *
 * @package PGModule\Entities
 */
class PGDomainEntitiesButton extends PGFrameworkFoundationsAbstractEntityPersisted implements PGDomainInterfacesEntitiesButtonInterface
{
    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->get('label');
    }

    /**
     * @inheritdoc
     */
    public function setLabel($label)
    {
        $this->set('label', $label);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getImageSrc()
    {
        return $this->get('image');
    }

    /**
     * @inheritdoc
     */
    public function setImageSrc($image)
    {
        $this->set('image', $image);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getImageHeight()
    {
        return (int) $this->get('height');
    }

    /**
     * @inheritdoc
     */
    public function setImageHeight($height)
    {
        $this->set('height', (int) $height);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPosition()
    {
        return (int) $this->get('position');
    }

    /**
     * @inheritdoc
     */
    public function setPosition($position)
    {
        $this->set('position', (int) $position);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDisplayType()
    {
        return (string) $this->get('displayType');
    }

    /**
     * @inheritdoc
     */
    public function setDisplayType($displayType)
    {
        $this->set('displayType', $displayType);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getIntegration()
    {
        return (string) $this->get('integration');
    }

    /**
     * @inheritdoc
     */
    public function setIntegration($integration)
    {
        $this->set('integration', $integration);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMaxAmount()
    {
        return (int) $this->get('maxAmount');
    }

    /**
     * @inheritdoc
     */
    public function setMaxAmount($maxAmount)
    {
        $this->set('maxAmount', (float) $maxAmount);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMinAmount()
    {
        return (int) $this->get('minAmount');
    }

    /**
     * @inheritdoc
     */
    public function setMinAmount($minAmount)
    {
        $this->set('minAmount', (float) $minAmount);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentMode()
    {
        return (string) $this->get('paymentMode');
    }

    /**
     * @inheritdoc
     */
    public function setPaymentMode($paymentMode)
    {
        $this->set('paymentMode', $paymentMode);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentType()
    {
        return (string) $this->get('paymentType');
    }

    /**
     * @inheritdoc
     */
    public function setPaymentType($paymentType)
    {
        $this->set('paymentType', $paymentType);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFirstPaymentPart()
    {
        return (int) $this->get('firstPaymentPart');
    }

    /**
     * @inheritdoc
     */
    public function setFirstPaymentPart($firstPaymentPart)
    {
        $this->set('firstPaymentPart', (int) $firstPaymentPart);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentNumber()
    {
        return (int) $this->get('paymentNumber');
    }

    /**
     * @inheritdoc
     */
    public function setPaymentNumber($paymentNumber)
    {
        $this->set('paymentNumber', (int) $paymentNumber);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentReport()
    {
        return (string) $this->get('paymentReport');
    }

    /**
     * @inheritdoc
     */
    public function setPaymentReport($paymentReport)
    {
        $this->set('paymentReport', $paymentReport);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDiscount()
    {
        return $this->get('discount');
    }

    /**
     * @inheritdoc
     */
    public function setDiscount($discount)
    {
        $this->set('discount', $discount);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isOrderRepeated()
    {
        return (bool) $this->get('orderRepeated');
    }

    /**
     * @inheritdoc
     */
    public function setOrderRepeated($isOrderRepeated)
    {
        $this->set('orderRepeated', $isOrderRepeated);

        return $this;
    }
}
