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
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGPayment\Interfaces\Entities;

use PGI\Module\PGDatabase\Interfaces\EntityPersistedInterface;
use PGI\Module\PGShop\Interfaces\Entities\CategoryEntityInterface;

/**
 * Interface ButtonEntityInterface
 * @package PGPayment\Interfaces\Entities
 */
interface ButtonEntityInterface extends EntityPersistedInterface
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
    public function getFilteredCategoryMode();

    /**
     * @return array
     */
    public function getFilteredCategoryPrimaries();

    /**
     * @return array
     */
    public function getFilteredCategories();


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
     * @param string $mode
     * @return self
     */
    public function setFilteredCategoryMode($mode);

    /**
     * @param array $primaries
     * @return self
     */
    public function setFilteredCategoryPrimaries($primaries);

    /**
     * @param CategoryEntityInterface[] $categories
     * @return self
     */
    public function setFilteredCategories(array $categories);

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
