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
 * @version   1.2.1
 *
 */

/**
 * Interface PGDomainInterfacesRepositoriesRecurringTransactionRepositoryInterface
 * @package PGDomain\Interfaces\Repositories
 */
interface PGDomainInterfacesRepositoriesRecurringTransactionRepositoryInterface extends PGFrameworkInterfacesRepositoryInterface
{
    /**
     * @param int $id
     * @return PGDomainInterfacesEntitiesRecurringTransactionInterface
     */
    public function findByPrimary($id);

    /**
     * @param string $pid
     * @return PGDomainInterfacesEntitiesRecurringTransactionInterface|null
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
     * @return PGDomainInterfacesEntitiesRecurringTransactionInterface
     */
    public function insert($pid, $id_order, $state, $stateOrderBefore, $mode, $amount, $rank);

    /**
     * @param PGDomainInterfacesEntitiesRecurringTransactionInterface $transaction
     * @param string $stateOrderAfter
     * @return bool
     */
    public function updateState(PGDomainInterfacesEntitiesRecurringTransactionInterface $transaction, $stateOrderAfter);
}
