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

namespace PGI\Module\PGIntl\Services\Upgrades;

use PGI\Module\PGDatabase\Services\Handlers\DatabaseHandler;
use PGI\Module\PGIntl\Services\Managers\TranslationManager;
use PGI\Module\PGModule\Components\Upgrade as UpgradeComponent;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;
use PGI\Module\PGShop\Services\Managers\ShopManager;
use Exception;

/**
 * Class RestoreButtonLabelsHandler
 * @package PGIntl\Services\Upgrades
 */
class RestoreButtonLabelsHandler implements UpgradeInterface
{
    const DEFAULT_LANGUAGE = 'fr';

    /** @var TranslationManager */
    private $translationManager;

    /** @var ShopManager */
    private $shopManager;

    /** @var DatabaseHandler */
    private $databaseHandler;

    private $bin;

    public function __construct(
        TranslationManager $translationManager,
        ShopManager $shopManager,
        DatabaseHandler $databaseHandler
    ) {
        $this->translationManager = $translationManager;
        $this->shopManager = $shopManager;
        $this->databaseHandler = $databaseHandler;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(UpgradeComponent $upgradeStage)
    {
        // Thrashing unused arguments
        $this->bin = $upgradeStage;

        $result = $this->databaseHandler->fetchArray(
            "SELECT `id`, `label`, `id_shop` FROM `%{database.entities.button.table}` WHERE 1;"
        );

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
