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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGModule\Interfaces\Entities;

use PGI\Module\PGDatabase\Interfaces\EntityPersistedInterface;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;

/**
 * Interface SettingEntityInterface
 * @package PGModule\Interfaces\Entities
 */
interface SettingEntityInterface extends EntityPersistedInterface
{
    /**
     * @return int
     */
    public function id();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param $value
     * @return self
     */
    public function setValue($value);

    /**
     * @return ShopEntityInterface|null
     */
    public function getShop();

    /**
     * @return int|null
     */
    public function getShopPrimary();
}
