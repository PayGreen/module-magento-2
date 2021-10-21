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

namespace PGI\Module\PGShop\Interfaces\Officers;

use PGI\Module\PGShop\Interfaces\Entities\CartEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ProductEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ShopableItemEntityInterface;

interface CartOfficerInterface
{
    /**
     * @param CartEntityInterface $cart
     * @param ProductEntityInterface $product
     * @param float $cost
     * @return ShopableItemEntityInterface
     */
    public function addItem(CartEntityInterface $cart, ProductEntityInterface $product, $cost);

    /**
     * @param CartEntityInterface $cart
     * @param ProductEntityInterface $product
     * @return bool
     */
    public function removeItem(CartEntityInterface $cart, ProductEntityInterface $product);
}
