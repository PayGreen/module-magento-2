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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGPayment\Entities;

use PGI\Module\PGDatabase\Foundations\AbstractEntityPersisted;
use PGI\Module\PGIntl\Services\Handlers\TranslationHandler;
use PGI\Module\PGPayment\Interfaces\Entities\ButtonEntityInterface;
use PGI\Module\PGShop\Interfaces\Repositories\CategoryRepositoryInterface;

/**
 * Class Button
 * @package PGPayment\Entities
 */
class Button extends AbstractEntityPersisted implements ButtonEntityInterface
{
    private $label = null;

    /** @var array */
    private $filteredCategories = array();

    public function toArray()
    {
        $data = parent::toArray();

        $data['label'] = $this->getLabel();

        return $data;
    }

    public function getLabel()
    {
        if (($this->label === null) && ($this->id() > 0)) {
            /** @var TranslationHandler $translationHandler */
            $translationHandler = $this->getService('handler.translation');

            $key = 'button-' . $this->id();
            $this->label = $translationHandler->translate($key);
        }

        return (string) $this->label;
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
     * @inheridoc
     */
    public function getFilteredCategoryMode()
    {
        return (string) $this->get('filtered_category_mode');
    }

    /**
     * @inheridoc
     */
    public function setFilteredCategoryMode($mode)
    {
        $this->filteredCategories = array();

        $this->set('filtered_category_mode', (string) $mode);

        return $this;
    }

    /**
     * @inheridoc
     */
    public function getFilteredCategoryPrimaries()
    {
        return (array) $this->get('filtered_category_primaries');
    }

    /**
     * @inheridoc
     */
    public function setFilteredCategoryPrimaries($primaries)
    {
        $this->set('filtered_category_primaries', (array) $primaries);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFilteredCategories()
    {
        /** @var CategoryRepositoryInterface $categoryRepository */
        $categoryRepository = $this->getService('repository.category');

        if (!empty($this->filteredCategories)) {
            return $this->filteredCategories;
        }

        $primaries = $this->getFilteredCategoryPrimaries();

        foreach ($primaries as $primary) {
            $category = $categoryRepository->findByPrimary($primary);

            if ($category !== null) {
                $this->filteredCategories[] = $categoryRepository->findByPrimary($primary);
            }
        }

        return $this->filteredCategories;
    }

    /**
     * @inheritdoc
     */
    public function setFilteredCategories(array $categories)
    {
        $this->filteredCategories = array();

        $primaries = array();

        foreach ($categories as $category) {
            $primaries[] = $category->id();
        }

        $this->setFilteredCategoryPrimaries($primaries);

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
