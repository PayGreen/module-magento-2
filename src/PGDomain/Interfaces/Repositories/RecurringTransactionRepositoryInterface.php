<?php
/**
 * 2014 - 2019 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.4
 */

/**
 * Interface PGDomainInterfacesRepositoriesRecurringTransactionRepositoryInterface
 * @package PGDomain\Interfaces\Repositories
 */
interface PGDomainInterfacesRepositoriesRecurringTransactionRepositoryInterface extends PGFrameworkInterfacesRepositoryWrappedEntityInterface
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
