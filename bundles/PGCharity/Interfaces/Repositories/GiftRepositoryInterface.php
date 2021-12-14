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

namespace PGI\Module\PGCharity\Interfaces\Repositories;

use PGI\Module\PGDatabase\Interfaces\RepositoryInterface;
use PGI\Module\PGCharity\Interfaces\Entities\GiftEntityInterface;

/**
 * Interface GiftRepositoryInterface
 * @package PGCharity\Interfaces\Repositories
 */
interface GiftRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $id
     * @return GiftEntityInterface|null
     */
    public function findByPrimary($id);

    /**
     * @param int $id_order
     * @return GiftEntityInterface|null
     */
    public function findByOrderPrimary($id_order);

    /**
     * @param string $reference
     * @param int $id_order
     * @param int $id_partnership
     * @param int $amount
     * @return GiftEntityInterface
     */
    public function create($reference, $id_order, $id_partnership, $amount);

    /**
     * @param GiftEntityInterface $gift
     * @return bool
     */
    public function insert(GiftEntityInterface $gift);

    /**
     * @param GiftEntityInterface $gift
     * @return bool
     */
    public function update(GiftEntityInterface $gift);

    /**
     * @param GiftEntityInterface $gift
     * @return bool
     */
    public function delete(GiftEntityInterface $gift);

    /**
     * @param int $dayIntervalBegin
     * @param int $dayIntervalEnd
     * @return int
     */
    public function getCountByDayInterval($dayIntervalBegin = 0, $dayIntervalEnd = 1);

    /**
     * @param int $dayIntervalBegin
     * @param int $dayIntervalEnd
     * @return float
     */
    public function getAmountByDayInterval($dayIntervalBegin = 0, $dayIntervalEnd = 1);
}
