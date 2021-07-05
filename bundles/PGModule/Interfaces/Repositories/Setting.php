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
 * @version   2.1.0
 *
 */

/**
 * Interface PGModuleInterfacesRepositoriesSetting
 * @package PGModule\Interfaces\Repositories
 */
interface PGModuleInterfacesRepositoriesSetting extends PGDatabaseInterfacesRepository
{
    /**
     * @param int|null $id_shop
     * @return PGModuleInterfacesEntitiesSetting[]
     */
    public function findAllByPrimaryShop($id_shop = null);

    /**
     * @param string $name
     * @param int|null $id_shop
     * @return PGModuleInterfacesEntitiesSetting
     */
    public function findOneByNameAndPrimaryShop($name, $id_shop = null);

    /**
     * @param string $name
     * @param int|null $id_shop
     * @return PGModuleInterfacesEntitiesSetting
     */
    public function create($name, $id_shop = null);

    /**
     * @param PGModuleInterfacesEntitiesSetting $setting
     * @return bool
     */
    public function insert(PGModuleInterfacesEntitiesSetting $setting);

    /**
     * @param PGModuleInterfacesEntitiesSetting $setting
     * @return bool
     */
    public function update(PGModuleInterfacesEntitiesSetting $setting);

    /**
     * @param PGModuleInterfacesEntitiesSetting $setting
     * @return bool
     */
    public function delete(PGModuleInterfacesEntitiesSetting $setting);
}
