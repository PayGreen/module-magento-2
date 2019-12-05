<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.4
 */

use Paygreen\Payment\Model\Button;

/**
 * Class PGModuleEntitiesButton
 *
 * @package PGModule\Entities
 * @method Button getLocalEntity()
 */
class PGModuleEntitiesButton extends PGFrameworkFoundationsAbstractEntityWrapped implements PGDomainInterfacesEntitiesButtonInterface
{
    protected function hydrateFromLocalEntity($localEntity)
    {
        // Do nothing.
    }

    /**
     * @inheritdoc
     */
    public function id()
    {
        return (int) $this->getLocalEntity()->getData('id');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return (string) $this->getLocalEntity()->getData('label');
    }

    /**
     * @inheritdoc
     */
    public function setLabel($label)
    {
        $this->getLocalEntity()->setData('label', $label);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getImageSrc()
    {
        return (string) $this->getLocalEntity()->getData('image');
    }

    /**
     * @inheritdoc
     */
    public function setImageSrc($image)
    {
        $this->getLocalEntity()->setData('image', $image);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getImageHeight()
    {
        return (int) $this->getLocalEntity()->getData('height');
    }

    /**
     * @inheritdoc
     */
    public function setImageHeight($height)
    {
        $this->getLocalEntity()->setData('height', $height);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPosition()
    {
        return (int) $this->getLocalEntity()->getData('position');
    }

    /**
     * @inheritdoc
     */
    public function setPosition($position)
    {
        $this->getLocalEntity()->setData('position', $position);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDisplayType()
    {
        return (string) $this->getLocalEntity()->getData('displayType');
    }

    /**
     * @inheritdoc
     */
    public function setDisplayType($displayType)
    {
        $this->getLocalEntity()->setData('displayType', $displayType);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getIntegration()
    {
        return (string) $this->getLocalEntity()->getData('integration');
    }

    /**
     * @inheritdoc
     */
    public function setIntegration($integration)
    {
        $this->getLocalEntity()->setData('integration', $integration);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMaxAmount()
    {
        return (int) $this->getLocalEntity()->getData('maxAmount');
    }

    /**
     * @inheritdoc
     */
    public function setMaxAmount($maxAmount)
    {
        $this->getLocalEntity()->setData('maxAmount', $maxAmount);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMinAmount()
    {
        return (int) $this->getLocalEntity()->getData('minAmount');
    }

    /**
     * @inheritdoc
     */
    public function setMinAmount($minAmount)
    {
        $this->getLocalEntity()->setData('minAmount', $minAmount);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentMode()
    {
        return (string) $this->getLocalEntity()->getData('paymentMode');
    }

    /**
     * @inheritdoc
     */
    public function setPaymentMode($paymentMode)
    {
        $this->getLocalEntity()->setData('paymentMode', $paymentMode);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentType()
    {
        return (string) $this->getLocalEntity()->getData('paymentType');
    }

    /**
     * @inheritdoc
     */
    public function setPaymentType($paymentType)
    {
        $this->getLocalEntity()->setData('paymentType', $paymentType);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFirstPaymentPart()
    {
        return (int) $this->getLocalEntity()->getData('firstPaymentPart');
    }

    /**
     * @inheritdoc
     */
    public function setFirstPaymentPart($firstPaymentPart)
    {
        $this->getLocalEntity()->setData('firstPaymentPart', $firstPaymentPart);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentNumber()
    {
        return (int) $this->getLocalEntity()->getData('paymentNumber');
    }

    /**
     * @inheritdoc
     */
    public function setPaymentNumber($paymentNumber)
    {
        $this->getLocalEntity()->setData('paymentNumber', $paymentNumber);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentReport()
    {
        return (string) $this->getLocalEntity()->getData('paymentReport');
    }

    /**
     * @inheritdoc
     */
    public function setPaymentReport($paymentReport)
    {
        $this->getLocalEntity()->setData('paymentReport', $paymentReport);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDiscount()
    {
        return $this->getLocalEntity()->getData('discount');
    }

    /**
     * @inheritdoc
     */
    public function setDiscount($discount)
    {
        $this->getLocalEntity()->setData('discount', $discount);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isOrderRepeated()
    {
        return (bool) $this->getLocalEntity()->getData('orderRepeated');
    }

    /**
     * @inheritdoc
     */
    public function setOrderRepeated($isOrderRepeated)
    {
        $this->getLocalEntity()->setData('orderRepeated', $isOrderRepeated);

        return $this;
    }

    public function toArray()
    {
        return array(
            'id' => $this->id(),
            'label' => $this->getLabel(),
            'image' => $this->getImageSrc(),
            'height' => $this->getImageHeight(),
            'position' => $this->getPosition(),
            'displayType' => $this->getDisplayType(),
            'integration' => $this->getIntegration(),
            'maxAmount' => $this->getMaxAmount(),
            'minAmount' => $this->getMinAmount(),
            'paymentMode' => $this->getPaymentMode(),
            'paymentType' => $this->getPaymentType(),
            'firstPaymentPart' => $this->getFirstPaymentPart(),
            'paymentNumber' => $this->getPaymentNumber(),
            'paymentReport' => $this->getPaymentReport(),
            'discount' => $this->getDiscount(),
            'orderRepeated' => $this->isOrderRepeated(),
        );
    }
}
