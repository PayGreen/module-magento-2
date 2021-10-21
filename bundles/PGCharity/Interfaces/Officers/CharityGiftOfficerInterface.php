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

namespace PGI\Module\PGCharity\Interfaces\Officers;

use Exception;
use PGI\Module\PGShop\Interfaces\Entities\ProductEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;

/**
 * Interface CharityGiftOfficerInterface
 * @package PGCharity\Interfaces\Interfaces
 */
interface CharityGiftOfficerInterface
{
    /**
     * @param ProductEntityInterface $gift
     * @param ShopEntityInterface $shop
     * @return bool
     */
    public function isValid($gift, ShopEntityInterface $shop);

    /**
     * @param ProductEntityInterface $gift
     * @param ShopEntityInterface $shop
     * @return bool
     */
    public function validate($gift, ShopEntityInterface $shop);

    /**
     * @param ProductEntityInterface $gift
     * @param ShopEntityInterface $shop
     * @return bool
     */
    public function disable($gift, ShopEntityInterface $shop);

    /**
     * @param ShopEntityInterface $shop
     * @return ProductEntityInterface
     */
    public function create(ShopEntityInterface $shop);

    /**
     * @param ShopEntityInterface $shop
     * @return int|null
     * @throws Exception
     */
    public function getPrimary(ShopEntityInterface $shop);
}
