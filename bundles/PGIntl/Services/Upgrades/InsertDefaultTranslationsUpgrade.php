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

/**
 * Class PGIntlServicesUpgradesInsertDefaultTranslationsUpgrade
 * @package PGIntl\Services\Upgrades
 */
class PGIntlServicesUpgradesInsertDefaultTranslationsUpgrade implements PGModuleInterfacesUpgrade
{
    /** @var PGIntlServicesHandlersTranslationHandler */
    private $translationHandler;

    /** @var PGShopServicesManagersShop */
    private $shopManager;

    public function __construct(
        PGIntlServicesHandlersTranslationHandler $translationHandler,
        PGShopServicesManagersShop $shopManager
    ) {
        $this->translationHandler = $translationHandler;
        $this->shopManager = $shopManager;
    }

    public function apply(PGModuleComponentsUpgrade $upgradeStage)
    {
        $codes = $upgradeStage->getConfig('codes');

        /** @var PGShopInterfacesEntitiesShop $shop */
        foreach ($this->shopManager->getAll() as $shop) {
            $this->translationHandler->insertDefaultTranslations($shop, $codes);
        }
    }
}
