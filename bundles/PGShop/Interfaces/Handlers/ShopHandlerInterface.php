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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGShop\Interfaces\Handlers;

use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;

/**
 * Interface ShopHandlerInterface
 * @package PGShop\Interfaces\Handlers
 */
interface ShopHandlerInterface
{
    /**
     * @return bool
     */
    public function isMultiShopActivated();

    /**
     * @return bool
     */
    public function isShopContext();

    /**
     * @return ShopEntityInterface
     */
    public function getCurrentShop();

    /**
     * @return int
     */
    public function getCurrentShopPrimary();

    /**
     * @param ShopEntityInterface $shop
     */
    public function defineCurrentShop(ShopEntityInterface $shop);
}
