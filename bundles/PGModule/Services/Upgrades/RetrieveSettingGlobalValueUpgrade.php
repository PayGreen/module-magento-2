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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGModule\Services\Upgrades;

use Exception;
use PGI\Module\PGModule\Components\Upgrade as UpgradeComponent;
use PGI\Module\PGModule\Interfaces\Entities\SettingEntityInterface;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;
use PGI\Module\PGModule\Services\Managers\SettingManager;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;
use PGI\Module\PGShop\Services\Managers\ShopManager;

/**
 * Class RetrieveSettingValueUpgrade
 * @package PGModule\Services\Upgrades
 */
class RetrieveSettingGlobalValueUpgrade implements UpgradeInterface
{
    /** @var SettingManager */
    private $settingManager;

    /** @var ShopManager */
    private $shopManager;

    public function __construct(SettingManager $settingManager, ShopManager $shopManager)
    {
        $this->settingManager = $settingManager;
        $this->shopManager = $shopManager;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(UpgradeComponent $upgradeStage)
    {
        $key_to_update = $upgradeStage->getConfig('key_to_update');
        $key_global_setting = $upgradeStage->getConfig('key_global_setting');

        if ($this->settingManager->hasByShop($key_global_setting)) {
            $global_setting = $this->settingManager->getByNameAndShop($key_global_setting);
            $this->settingManager->delete($global_setting);

            foreach ($this->shopManager->getAll() as $shop) {
                $this->applyForShop($key_to_update, $global_setting, $shop);
            }
        } else {
            throw new Exception("'$key_global_setting' is not a global setting.");
        }
    }

    /**
     * @param string $key_to_update
     * @param SettingEntityInterface $global_setting
     * @param ShopEntityInterface $shop
     * @throws Exception
     */
    public function applyForShop($key_to_update, SettingEntityInterface $global_setting, ShopEntityInterface $shop)
    {
        $global_setting_value = $global_setting->getValue();

        if ($this->settingManager->hasByShop($key_to_update, $shop)) {
            $setting = $this->settingManager->getByNameAndShop($key_to_update, $shop);
            $setting->setValue($global_setting_value);

            $this->settingManager->update($setting);
        } else {
            $this->settingManager->insert($key_to_update, $global_setting_value, $shop);
        }
    }
}
