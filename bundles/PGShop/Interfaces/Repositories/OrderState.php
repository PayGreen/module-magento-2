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
 * @version   2.0.0
 *
 */

/**
 * Interface PGShopInterfacesRepositoriesOrderState
 * @package PGShop\Interfaces\Repositories
 */
interface PGShopInterfacesRepositoriesOrderState extends PGDatabaseInterfacesRepositoryWrappedEntity
{
    /**
     * @param string $code
     * @param string $name
     * @param array $metadata
     * @return PGShopInterfacesEntitiesOrderState
     */
    public function create($code, $name, array $metadata = array());

    /**
     * @param int $id
     * @return null|PGShopInterfacesEntitiesOrderState
     */
    public function findByPrimary($id);
}
