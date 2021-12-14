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

namespace PGI\Module\PGPayment\Interfaces\Repositories;

use PGI\Module\PGDatabase\Interfaces\RepositoryInterface;
use PGI\Module\PGPayment\Interfaces\Entities\RecurringTransactionEntityInterface;

/**
 * Interface RecurringTransactionRepositoryInterface
 * @package PGPayment\Interfaces\Repositories
 */
interface RecurringTransactionRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $id
     * @return RecurringTransactionEntityInterface
     */
    public function findByPrimary($id);

    /**
     * @param string $pid
     * @return RecurringTransactionEntityInterface|null
     */
    public function findByPid($pid);

    /**
     * @param string $pid
     * @param int $id_order
     * @param string $state
     * @param string $stateOrderBefore
     * @param string $mode
     * @param int $amount
     * @param int $rank
     * @return RecurringTransactionEntityInterface
     */
    public function insert($pid, $id_order, $state, $stateOrderBefore, $mode, $amount, $rank);

    /**
     * @param RecurringTransactionEntityInterface $transaction
     * @param string $stateOrderAfter
     * @return bool
     */
    public function updateState(
        RecurringTransactionEntityInterface $transaction,
        $stateOrderAfter
    );
}
