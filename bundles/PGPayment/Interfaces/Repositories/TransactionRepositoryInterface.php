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

namespace PGI\Module\PGPayment\Interfaces\Repositories;

use PGI\Module\PGDatabase\Interfaces\RepositoryInterface;
use PGI\Module\PGPayment\Interfaces\Entities\TransactionEntityInterface;

/**
 * Interface TransactionRepositoryInterface
 * @package PGPayment\Interfaces\Repositories
 */
interface TransactionRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $id
     * @return TransactionEntityInterface|null
     */
    public function findByPrimary($id);

    /**
     * @param int $id_order
     * @return TransactionEntityInterface|null
     */
    public function findByOrderPrimary($id_order);

    /**
     * @param string $pid
     * @return TransactionEntityInterface|null
     */
    public function findByPid($pid);

    /**
     * @param int $id_order
     * @return int
     */
    public function countByOrderPrimary($id_order);

    /**
     * @return TransactionEntityInterface
     */
    public function create();

    /**
     * @param TransactionEntityInterface $transaction
     * @return bool
     */
    public function insert(TransactionEntityInterface $transaction);

    /**
     * @param TransactionEntityInterface $transaction
     * @return bool
     */
    public function update(TransactionEntityInterface $transaction);

    /**
     * @param TransactionEntityInterface $transaction
     * @return bool
     */
    public function delete(TransactionEntityInterface $transaction);

    /**
     * @param int $dayIntervalBegin
     * @param int $dayIntervalEnd
     * @return array
     */
    public function findAllByDayInterval($dayIntervalBegin = 0, $dayIntervalEnd = 1);

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
