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
 * @version   1.2.3
 *
 */

/**
 * Interface PGDomainInterfacesRepositoriesButtonRepositoryInterface
 * @package PGDomain\Interfaces\Repositories
 */
interface PGDomainInterfacesRepositoriesButtonRepositoryInterface extends PGFrameworkInterfacesRepositoryInterface
{
    /**
     * @param $id int
     * @return PGDomainInterfacesEntitiesButtonInterface|null
     */
    public function findByPrimary($id);

    /**
     * @return PGDomainInterfacesEntitiesButtonInterface[]
     */
    public function findAll();

    /**
     * @return PGDomainInterfacesEntitiesButtonInterface
     */
    public function create();

    /**
     * @return int
     */
    public function count();

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return bool
     */
    public function insert(PGDomainInterfacesEntitiesButtonInterface $button);

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return bool
     */
    public function update(PGDomainInterfacesEntitiesButtonInterface $button);

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return bool
     */
    public function delete(PGDomainInterfacesEntitiesButtonInterface $button);
}
