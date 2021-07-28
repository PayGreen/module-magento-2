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

namespace PGI\Module\PGMagento\Services\Upgrades;

use Magento\Framework\App\ObjectManager as LocalObjectManager;
use Magento\Framework\App\Config\ScopeConfigInterface as LocalScopeConfigInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface as LocalConfigInterface;
use PGI\Module\PGModule\Components\Upgrade as UpgradeComponent;
use PGI\Module\PGModule\Interfaces\Repositories\SettingRepositoryInterface;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;
use PGI\Module\PGModule\Services\Officers\SettingsDatabaseOfficer;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;
use PGI\Module\PGShop\Interfaces\Handlers\ShopHandlerInterface;
use Exception;

class RestoreSettingsUpgrade implements UpgradeInterface
{
    /** @var LocalScopeConfigInterface */
    private $scopeConfig;

    /** @var LocalConfigInterface */
    private $resourceConfig;

    /** @var SettingRepositoryInterface */
    private $settingRepository;

    /** @var ShopHandlerInterface */
    private $shopHandler;

    /** @var SettingsDatabaseOfficer */
    private $basicOfficer;

    /** @var SettingsDatabaseOfficer */
    private $globalOfficer;

    public function __construct(
        LocalObjectManager $objectManager,
        SettingRepositoryInterface $settingRepository,
        ShopHandlerInterface $shopHandler,
        SettingsDatabaseOfficer $basicOfficer,
        SettingsDatabaseOfficer $globalOfficer
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
    public function apply(UpgradeComponent $upgradeStage)
    {
        /** @var ShopEntityInterface $shop */
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
