<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGIntl\Services\Upgrades;

use PGI\Module\PGIntl\Services\Managers\TranslationManager;
use PGI\Module\PGModule\Components\UpgradeStage as UpgradeComponent;
use PGI\Module\PGModule\Interfaces\Entities\SettingEntityInterface;
use PGI\Module\PGModule\Interfaces\Repositories\SettingRepositoryInterface;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;
use PGI\Module\PGShop\Services\Managers\ShopManager;
use Exception;

/**
 * Class RestoreTranslationsHandler
 * @package PGIntl\Services\Upgrades
 */
class RestoreTranslationsHandler implements UpgradeInterface
{
    const DEFAULT_LANGUAGE = 'fr';

    /** @var TranslationManager */
    private $translationManager;

    /** @var ShopManager */
    private $shopManager;

    /** @var SettingRepositoryInterface */
    private $settingRepository;

    /** @var Settings */
    private $settings;

    public function __construct(
        TranslationManager $translationManager,
        ShopManager $shopManager,
        SettingRepositoryInterface $settingRepository,
        Settings $settings
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
    public function apply(UpgradeComponent $upgradeStage)
    {
        $keys = $upgradeStage->getConfig('keys');

        /** @var ShopEntityInterface $shop */
        foreach ($this->shopManager->getAll() as $shop) {
            foreach ($keys as $code => $key) {
                /** @var SettingEntityInterface $setting */
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
