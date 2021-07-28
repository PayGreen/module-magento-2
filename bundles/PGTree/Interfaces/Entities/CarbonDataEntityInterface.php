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

namespace PGI\Module\PGTree\Interfaces\Entities;

use PGI\Module\PGDatabase\Interfaces\EntityPersistedInterface;
use DateTime;
use Exception;

/**
 * Interface CarbonDataEntityInterface
 * @package PGTree\Interfaces\Entities
 */
interface CarbonDataEntityInterface extends EntityPersistedInterface
{
    /**
     * @return int
     */
    public function getOrderId();

    /**
     * @return int
     */
    public function getUserId();

    /**
     * @return string
     */
    public function getFingerprintId();

    /**
     * @return float
     */
    public function getFootprint();

    /**
     * @return float
     */
    public function getCarbonOffset();

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getCreatedAt();

    /**
     * @param int $id_order
     * @return self
     */
    public function setOrderId($id_order);

    /**
     * @param int $id_user
     * @return self
     */
    public function setUserId($id_user);

    /**
     * @param string $id_fingerprint
     * @return self
     */
    public function setFingerprintId($id_fingerprint);

    /**
     * @param float $footprint
     * @return self
     */
    public function setFootprint($footprint);

    /**
     * @param float $carbonOffset
     * @return self
     */
    public function setCarbonOffset($carbonOffset);

    /**
     * @param DateTime $createdAt
     * @return self
     */
    public function setCreatedAt(DateTime $createdAt);
}
