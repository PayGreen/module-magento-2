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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGPayment\Interfaces\Entities;

use PGI\Module\PGDatabase\Interfaces\EntityPersistedInterface;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;
use DateTime;

/**
 * Interface ProcessingEntityInterface
 * @package PGPayment\Interfaces\Entities
 */
interface ProcessingEntityInterface extends EntityPersistedInterface
{
    /**
     * @return string
     */
    public function getShopPrimary();

    /**
     * @return ShopEntityInterface
     */
    public function getShop();

    /**
     * @return string
     */
    public function getReference();

    /**
     * @return bool
     */
    public function isSuccess();

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return string
     */
    public function getPid();

    /**
     * @return string
     */
    public function getPidStatus();

    /**
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * @return DateTime[]
     */
    public function getEchoes();

    /**
     * @param DateTime $echo
     * @return self
     */
    public function addEcho(DateTime $echo);

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @return float
     */
    public function getUserAmount();

    /**
     * @return int|null
     */
    public function getOrderPrimary();

    /**
     * @return OrderEntityInterface|null
     */
    public function getOrder();

    /**
     * @return string|null
     */
    public function getOrderStateFrom();

    /**
     * @return string|null
     */
    public function getOrderStateTo();
}
