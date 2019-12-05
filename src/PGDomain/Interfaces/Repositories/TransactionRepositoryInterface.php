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
 * @version   0.3.5
 */

/**
 * Interface PGDomainInterfacesRepositoriesTransactionRepositoryInterface
 * @package PGDomain\Interfaces\Repositories
 */
interface PGDomainInterfacesRepositoriesTransactionRepositoryInterface extends PGFrameworkInterfacesRepositoryWrappedEntityInterface
{
    /**
     * @param int $id
     * @return PGDomainInterfacesEntitiesTransactionInterface|null
     */
    public function findByPrimary($id);

    /**
     * @param int $id_order
     * @return PGDomainInterfacesEntitiesTransactionInterface|null
     */
    public function findByOrderPrimary($id_order);

    /**
     * @param string $pid
     * @return PGDomainInterfacesEntitiesTransactionInterface|null
     */
    public function findByPid($pid);

    /**
     * @param int $id_order
     * @return int
     */
    public function countByOrderPrimary($id_order);

    /**
     * @return PGDomainInterfacesEntitiesTransactionInterface
     */
    public function create();

    /**
     * @param PGDomainInterfacesEntitiesTransactionInterface $transaction
     * @return bool
     */
    public function insert(PGDomainInterfacesEntitiesTransactionInterface $transaction);

    /**
     * @param PGDomainInterfacesEntitiesTransactionInterface $transaction
     * @return bool
     */
    public function update(PGDomainInterfacesEntitiesTransactionInterface $transaction);

    /**
     * @param PGDomainInterfacesEntitiesTransactionInterface $transaction
     * @return bool
     */
    public function delete(PGDomainInterfacesEntitiesTransactionInterface $transaction);
}
