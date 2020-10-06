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

class PGIntlServicesUpgradesRestoreButtonLabelsUpgrade implements PGFrameworkInterfacesUpgradeInterface
{
    const DEFAULT_LANGUAGE = 'fr';

    /** @var PGIntlServicesManagersTranslationManager */
    private $translationManager;

    /** @var PGDomainServicesManagersShopManager */
    private $shopManager;

    /** @var PGFrameworkServicesHandlersDatabaseHandler */
    private $databaseHandler;

    public function __construct(
        PGIntlServicesManagersTranslationManager $translationManager,
        PGDomainServicesManagersShopManager $shopManager,
        PGFrameworkServicesHandlersDatabaseHandler $databaseHandler
    ) {
        $this->translationManager = $translationManager;
        $this->shopManager = $shopManager;
        $this->databaseHandler = $databaseHandler;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(PGFrameworkComponentsUpgradeStage $upgradeStage)
    {
        $result = $this->databaseHandler->fetchArray("SELECT `id`, `label`, `id_shop` FROM `%{database.entities.button.table}` WHERE 1;");

        foreach ($result as $data) {
            $shop = $this->shopManager->getByPrimary($data['id_shop']);
            $code = 'button-' . $data['id'];
            $texts = array(
                self::DEFAULT_LANGUAGE => $data['label']
            );

            $this->translationManager->saveByCode($code, $texts, $shop);
        }

        return true;
    }
}
