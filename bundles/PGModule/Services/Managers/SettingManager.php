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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGModule\Services\Managers;

use PGI\Module\PGDatabase\Foundations\AbstractManager;
use PGI\Module\PGModule\Interfaces\Entities\SettingEntityInterface;
use PGI\Module\PGModule\Interfaces\Repositories\SettingRepositoryInterface;
use PGI\Module\PGModule\Services\Officers\SettingsDatabaseOfficer;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;

/**
 * Class SettingManager
 *
 * @package PGModule\Services\Managers
 * @method SettingRepositoryInterface getRepository()
 */
class SettingManager extends AbstractManager
{
    public function getAllByShop(ShopEntityInterface $shop = null)
    {
        $id_shop = ($shop !== null) ? $shop->id() : null;

        $data = array();

        $settings = $this->getRepository()->findAllByPrimaryShop($id_shop);

        /** @var SettingEntityInterface $setting */
        foreach ($settings as $setting) {
            $name = $setting->getName();

            $data[$name] = $setting;
        }

        return $data;
    }

    public function getByNameAndShop($name, ShopEntityInterface $shop = null)
    {
        $id_shop = ($shop !== null) ? $shop->id() : null;

        return $this->getRepository()->findOneByNameAndPrimaryShop($name, $id_shop);
    }

    public function hasByShop($name, ShopEntityInterface $shop = null)
    {
        $id_shop = ($shop !== null) ? $shop->id() : null;

        $setting = $this->getRepository()->findOneByNameAndPrimaryShop($name, $id_shop);

        return ($setting !== null);
    }

    public function insert($name, $value, ShopEntityInterface $shop = null)
    {
        $id_shop = ($shop !== null) ? $shop->id() : null;

        $setting = $this->getRepository()->create($name, $id_shop);

        $setting->setValue($value);

        $this->getRepository()->insert($setting);

        $this->clean();

        return $setting;
    }

    public function update(SettingEntityInterface $setting)
    {
        $result = $this->getRepository()->update($setting);

        if ($result) {
            $this->clean();
        }

        return $result;
    }

    public function delete(SettingEntityInterface $setting)
    {
        $result = $this->getRepository()->delete($setting);

        if ($result) {
            $this->clean();
        }

        return $result;
    }

    private function clean()
    {
        /** @var SettingsDatabaseOfficer $settingDatabaseBasicOfficer */
        $settingDatabaseBasicOfficer = $this->getService('officer.settings.database.basic');

        /** @var SettingsDatabaseOfficer $settingDatabaseGlobalOfficer */
        $settingDatabaseGlobalOfficer = $this->getService('officer.settings.database.global');

        $settingDatabaseBasicOfficer->clean();
        $settingDatabaseGlobalOfficer->clean();
    }
}
