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
 * @version   2.0.2
 *
 */

/**
 * Interface PGPaymentInterfacesRepositoriesTransactionRepositoryInterface
 * @package PGPayment\Interfaces\Repositories
 */
interface PGPaymentInterfacesRepositoriesTransactionRepositoryInterface extends PGDatabaseInterfacesRepository
{
    /**
     * @param int $id
     * @return PGPaymentInterfacesEntitiesTransactionInterface|null
     */
    public function findByPrimary($id);

    /**
     * @param int $id_order
     * @return PGPaymentInterfacesEntitiesTransactionInterface|null
     */
    public function findByOrderPrimary($id_order);

    /**
     * @param string $pid
     * @return PGPaymentInterfacesEntitiesTransactionInterface|null
     */
    public function findByPid($pid);

    /**
     * @param int $id_order
     * @return int
     */
    public function countByOrderPrimary($id_order);

    /**
     * @return PGPaymentInterfacesEntitiesTransactionInterface
     */
    public function create();

    /**
     * @param PGPaymentInterfacesEntitiesTransactionInterface $transaction
     * @return bool
     */
    public function insert(PGPaymentInterfacesEntitiesTransactionInterface $transaction);

    /**
     * @param PGPaymentInterfacesEntitiesTransactionInterface $transaction
     * @return bool
     */
    public function update(PGPaymentInterfacesEntitiesTransactionInterface $transaction);

    /**
     * @param PGPaymentInterfacesEntitiesTransactionInterface $transaction
     * @return bool
     */
    public function delete(PGPaymentInterfacesEntitiesTransactionInterface $transaction);
}
