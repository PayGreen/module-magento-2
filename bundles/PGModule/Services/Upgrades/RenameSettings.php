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
 * Class PGModuleServicesUpgradesRenameSettings
 * @package PGModule\Services\Upgrades
 */
class PGModuleServicesUpgradesRenameSettings implements PGModuleInterfacesUpgrade
{
    /** @var PGModuleServicesManagersSetting */
    private $settingManager;

    /** @var PGShopServicesManagersShop */
    private $shopManager;

    public function __construct(
        PGModuleServicesManagersSetting $settingManager,
        PGShopServicesManagersShop $shopManager)
    {
        $this->settingManager = $settingManager;
        $this->shopManager = $shopManager;
    }

    /**
     * @inheritDoc
     */
    public function apply(PGModuleComponentsUpgrade $upgradeStage)
    {
        foreach ($this->shopManager->getAll() as $shop) {
            $this->applyForShop($upgradeStage, $shop);
        }

        $this->applyForShop($upgradeStage);
    }

    /**
     * @param PGModuleComponentsUpgrade $upgradeStage
     * @param PGShopInterfacesEntitiesShop $shop
     */
    public function applyForShop(PGModuleComponentsUpgrade $upgradeStage, PGShopInterfacesEntitiesShop $shop = null)
    {
        $mapping = $upgradeStage->getConfig('mapping');

        foreach ($mapping as $oldKey => $newKey) {
            /** @var PGModuleInterfacesEntitiesSetting $setting */
            $setting = $this->settingManager->getByNameAndShop($oldKey, $shop);

            if($setting !== null) {
                $this->settingManager->insert($newKey,$setting->getValue(), $shop);
            }
        }
    }
}
