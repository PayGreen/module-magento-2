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

namespace PGI\Module\PGModule\Services\Upgrades;

use PGI\Module\PGModule\Components\UpgradeStage as UpgradeComponent;
use PGI\Module\PGModule\Interfaces\Entities\SettingEntityInterface;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;
use PGI\Module\PGModule\Services\Managers\SettingManager;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;
use PGI\Module\PGShop\Services\Managers\ShopManager;

/**
 * Class RenameSettingsUpgrade
 * @package PGModule\Services\Upgrades
 */
class RenameSettingsUpgrade implements UpgradeInterface
{
    /** @var SettingManager */
    private $settingManager;

    /** @var ShopManager */
    private $shopManager;

    public function __construct(
        SettingManager $settingManager,
        ShopManager $shopManager
    ) {
        $this->settingManager = $settingManager;
        $this->shopManager = $shopManager;
    }

    /**
     * @inheritDoc
     */
    public function apply(UpgradeComponent $upgradeStage)
    {
        foreach ($this->shopManager->getAll() as $shop) {
            $this->applyForShop($upgradeStage, $shop);
        }

        $this->applyForShop($upgradeStage);
    }

    /**
     * @param UpgradeComponent $upgradeStage
     * @param ShopEntityInterface $shop
     */
    public function applyForShop(UpgradeComponent $upgradeStage, ShopEntityInterface $shop = null)
    {
        $mapping = $upgradeStage->getConfig('mapping');

        foreach ($mapping as $oldKey => $newKey) {
            /** @var SettingEntityInterface $setting */
            $setting = $this->settingManager->getByNameAndShop($oldKey, $shop);

            if ($setting !== null) {
                $this->settingManager->insert($newKey, $setting->getValue(), $shop);
            }
        }
    }
}
