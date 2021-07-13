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
 * Class PGModuleServicesManagersSetting
 *
 * @package PGModule\Services\Managers
 * @method PGModuleInterfacesRepositoriesSetting getRepository()
 */
class PGModuleServicesManagersSetting extends PGDatabaseFoundationsManager
{
    public function getAllByShop(PGShopInterfacesEntitiesShop $shop = null)
    {
        $id_shop = ($shop !== null) ? $shop->id() : null;

        $data = array();

        $settings = $this->getRepository()->findAllByPrimaryShop($id_shop);

        /** @var PGModuleInterfacesEntitiesSetting $setting */
        foreach ($settings as $setting) {
            $name = $setting->getName();

            $data[$name] = $setting;
        }

        return $data;
    }

    public function getByNameAndShop($name, PGShopInterfacesEntitiesShop $shop = null)
    {
        $id_shop = ($shop !== null) ? $shop->id() : null;

        return $this->getRepository()->findOneByNameAndPrimaryShop($name, $id_shop);
    }

    public function hasByShop($name, PGShopInterfacesEntitiesShop $shop = null)
    {
        $id_shop = ($shop !== null) ? $shop->id() : null;

        $setting = $this->getRepository()->findOneByNameAndPrimaryShop($name, $id_shop);

        return ($setting !== null);
    }

    public function insert($name, $value, PGShopInterfacesEntitiesShop $shop = null)
    {
        $id_shop = ($shop !== null) ? $shop->id() : null;

        $setting = $this->getRepository()->create($name, $id_shop);

        $setting->setValue($value);

        $this->getRepository()->insert($setting);

        return $setting;
    }

    public function update(PGModuleInterfacesEntitiesSetting $setting)
    {
        return $this->getRepository()->update($setting);
    }

    public function delete(PGModuleInterfacesEntitiesSetting $setting)
    {
        return $this->getRepository()->delete($setting);
    }
}
