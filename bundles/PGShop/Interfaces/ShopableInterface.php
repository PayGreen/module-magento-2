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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGShop\Interfaces;

use PGI\Module\PGDatabase\Interfaces\EntityWrappedInterface;
use PGI\Module\PGShop\Interfaces\Entities\AddressEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\CarrierEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ShopableItemEntityInterface;

/**
 * Interface ShopableInterface
 * @package PGShop\Interfaces
 */
interface ShopableInterface extends EntityWrappedInterface
{
    /**
     * @return float
     */
    public function getShippingWeight();

    /**
     * @return AddressEntityInterface|null
     */
    public function getShippingAddress();

    /**
     * @return ShopableItemEntityInterface[]
     */
    public function getItems();

    /**
     * @return CarrierEntityInterface|null
     */
    public function getCarrier();
}
