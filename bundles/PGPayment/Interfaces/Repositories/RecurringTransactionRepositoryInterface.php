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
 * @version   2.1.1
 *
 */

/**
 * Interface PGPaymentInterfacesRepositoriesRecurringTransactionRepositoryInterface
 * @package PGPayment\Interfaces\Repositories
 */
interface PGPaymentInterfacesRepositoriesRecurringTransactionRepositoryInterface extends PGDatabaseInterfacesRepository
{
    /**
     * @param int $id
     * @return PGPaymentInterfacesEntitiesRecurringTransactionInterface
     */
    public function findByPrimary($id);

    /**
     * @param string $pid
     * @return PGPaymentInterfacesEntitiesRecurringTransactionInterface|null
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
     * @return PGPaymentInterfacesEntitiesRecurringTransactionInterface
     */
    public function insert($pid, $id_order, $state, $stateOrderBefore, $mode, $amount, $rank);

    /**
     * @param PGPaymentInterfacesEntitiesRecurringTransactionInterface $transaction
     * @param string $stateOrderAfter
     * @return bool
     */
    public function updateState(
        PGPaymentInterfacesEntitiesRecurringTransactionInterface $transaction,
        $stateOrderAfter
    );
}
