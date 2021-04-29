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
 * @version   2.0.1
 *
 */

/**
 * Class PGIntlServicesUpgradesRestoreTranslationsUpgrade
 * @package PGIntl\Services\Upgrades
 */
class PGIntlServicesUpgradesRestoreTranslationsUpgrade implements PGModuleInterfacesUpgrade
{
    const DEFAULT_LANGUAGE = 'fr';

    /** @var PGIntlServicesManagersTranslationManager */
    private $translationManager;

    /** @var PGShopServicesManagersShop */
    private $shopManager;

    /** @var PGModuleInterfacesRepositoriesSetting */
    private $settingRepository;

    /** @var PGModuleServicesSettings */
    private $settings;

    public function __construct(
        PGIntlServicesManagersTranslationManager $translationManager,
        PGShopServicesManagersShop $shopManager,
        PGModuleInterfacesRepositoriesSetting $settingRepository,
        PGModuleServicesSettings $settings
    ) {
        $this->translationManager = $translationManager;
        $this->shopManager = $shopManager;
        $this->settingRepository = $settingRepository;
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(PGModuleComponentsUpgrade $upgradeStage)
    {
        $keys = $upgradeStage->getConfig('keys');

        /** @var PGShopInterfacesEntitiesShop $shop */
        foreach ($this->shopManager->getAll() as $shop) {
            foreach ($keys as $code => $key) {
                /** @var PGModuleInterfacesEntitiesSetting $setting */
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

        $this->settings->clean();

        return true;
    }
}
