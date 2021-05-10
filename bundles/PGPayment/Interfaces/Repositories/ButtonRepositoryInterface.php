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
 * Interface PGPaymentInterfacesRepositoriesButtonRepositoryInterface
 * @package PGPayment\Interfaces\Repositories
 */
interface PGPaymentInterfacesRepositoriesButtonRepositoryInterface extends PGDatabaseInterfacesRepository
{
    /**
     * @param $id int
     * @return PGPaymentInterfacesEntitiesButtonInterface|null
     */
    public function findByPrimary($id);

    /**
     * @return PGPaymentInterfacesEntitiesButtonInterface[]
     */
    public function findAll();

    /**
     * @return PGPaymentInterfacesEntitiesButtonInterface
     */
    public function create();

    /**
     * @return int
     */
    public function count();

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @return bool
     */
    public function insert(PGPaymentInterfacesEntitiesButtonInterface $button);

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @return bool
     */
    public function update(PGPaymentInterfacesEntitiesButtonInterface $button);

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @return bool
     */
    public function delete(PGPaymentInterfacesEntitiesButtonInterface $button);
}
