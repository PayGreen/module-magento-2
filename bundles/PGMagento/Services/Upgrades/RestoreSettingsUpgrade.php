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

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;

class PGMagentoServicesUpgradesRestoreSettingsUpgrade implements PGModuleInterfacesUpgrade
{
    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var ConfigInterface */
    private $resourceConfig;

    /** @var PGModuleInterfacesRepositoriesSetting */
    private $settingRepository;

    /** @var PGShopInterfacesShopHandler */
    private $shopHandler;

    /** @var PGModuleServicesOfficersSettingsDatabase */
    private $basicOfficer;

    /** @var PGModuleServicesOfficersSettingsDatabase */
    private $globalOfficer;

    public function __construct(
        ObjectManager $objectManager,
        PGModuleInterfacesRepositoriesSetting $settingRepository,
        PGShopInterfacesShopHandler $shopHandler,
        PGModuleServicesOfficersSettingsDatabase $basicOfficer,
        PGModuleServicesOfficersSettingsDatabase $globalOfficer
    ) {
        $this->scopeConfig = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->resourceConfig = $objectManager->get('Magento\Framework\App\Config\ConfigResource\ConfigInterface');
        $this->settingRepository = $settingRepository;
        $this->shopHandler = $shopHandler;
        $this->basicOfficer = $basicOfficer;
        $this->globalOfficer = $globalOfficer;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(PGModuleComponentsUpgrade $upgradeStage)
    {
        /** @var PGShopInterfacesEntitiesShop $shop */
        $shop = $this->shopHandler->getCurrentShop();
        $keys = $upgradeStage->getConfig('keys');

        foreach ($keys as $oldKey => $newKey) {
            $setting = $this->settingRepository->findOneByNameAndPrimaryShop($newKey);

            if ($setting === null) {
                    $value = $this->scopeConfig->getValue("payment/paygreen/$oldKey");

                    if ($value) {
                        $setting = $this->settingRepository->create($newKey, $shop->id());

                        $setting->setValue($value);

                        if ($this->settingRepository->insert($setting)) {
                            $this->resourceConfig->deleteConfig("payment/paygreen/$oldKey");
                        }
                    }
            }
        }

        $this->basicOfficer->clean();
        $this->globalOfficer->clean();

        return true;
    }
}
