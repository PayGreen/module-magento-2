<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.2.0
 */

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;

class PGIntlServicesUpgradesRestoreTranslationsUpgrade implements PGFrameworkInterfacesUpgradeInterface
{
    const DEFAULT_LANGUAGE = 'fr';

    /** @var PGIntlServicesManagersTranslationManager */
    private $translationManager;

    /** @var PGDomainServicesManagersShopManager */
    private $shopManager;

    /** @var PGFrameworkInterfacesRepositoriesSettingRepositoryInterface */
    private $settingRepository;

    /** @var PGFrameworkServicesOfficersSettingsDatabaseOfficer */
    private $basicOfficer;

    /** @var PGFrameworkServicesOfficersSettingsDatabaseOfficer */
    private $globalOfficer;

    public function __construct(
        PGIntlServicesManagersTranslationManager $translationManager,
        PGDomainServicesManagersShopManager $shopManager,
        PGFrameworkInterfacesRepositoriesSettingRepositoryInterface $settingRepository,
        PGFrameworkServicesOfficersSettingsDatabaseOfficer $basicOfficer,
        PGFrameworkServicesOfficersSettingsDatabaseOfficer $globalOfficer
    ) {
        $this->translationManager = $translationManager;
        $this->shopManager = $shopManager;
        $this->settingRepository = $settingRepository;
        $this->basicOfficer = $basicOfficer;
        $this->globalOfficer = $globalOfficer;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(PGFrameworkComponentsUpgradeStage $upgradeStage)
    {
        $keys = $upgradeStage->getConfig('keys');

        /** @var PGDomainInterfacesEntitiesShopInterface $shop */
        foreach ($this->shopManager->getAll() as $shop) {

            foreach ($keys as $code => $key) {
                /** @var PGFrameworkInterfacesEntitiesSettingInterface $setting */
                $setting = $this->settingRepository->findOneByNameAndPrimaryShop($key, $shop->id());

                if ($setting !== null) {
                    $translation = $this->translationManager->getByCode($code);

                    $translation[self::DEFAULT_LANGUAGE] = $setting->getValue();

                    if ($this->translationManager->saveByCode($code, $translation, $shop)) {
                        $this->settingRepository->delete($setting);
                    }
                }
            }
        }

        $this->basicOfficer->clear();
        $this->globalOfficer->clear();

        return true;
    }
}
