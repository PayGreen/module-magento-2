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

namespace PGI\Module\PGPayment\Services\Upgrades;

use PGI\Module\PGDatabase\Services\Handlers\DatabaseHandler;
use PGI\Module\PGModule\Components\UpgradeStage as UpgradeComponent;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;
use PGI\Module\PGModule\Services\Managers\SettingManager;
use PGI\Module\PGShop\Services\Managers\ShopManager;

/**
 * Class InsitePaymentUpgrade
 * @package PGFramework\Services\Upgrades
 */
class InsitePaymentUpgrade implements UpgradeInterface
{
    /** @var DatabaseHandler */
    private $databaseHandler;

    /** @var ShopManager */
    private $shopManager;

    /** @var SettingManager */
    private $settingManager;

    private $bin;

    public function __construct(
        DatabaseHandler $databaseHandler,
        ShopManager $shopManager,
        SettingManager $settingManager
    ) {
        $this->databaseHandler = $databaseHandler;
        $this->shopManager = $shopManager;
        $this->settingManager = $settingManager;
    }

    /**
     * @inheritDoc
     */
    public function apply(UpgradeComponent $upgradeStage)
    {
        // Thrashing unused arguments
        $this->bin = $upgradeStage;

        foreach ($this->shopManager->getAll() as $shop) {
            $result = $this->databaseHandler->fetchColumn(
                "SELECT `integration` FROM `%{database.entities.button.table}` WHERE `id_shop` = {$shop->id()};"
            );

            if (in_array('INSITE', $result)) {
                $settingEntityInterface = $this->settingManager->getByNameAndShop("behavior_payment_insite", $shop);
                if ($settingEntityInterface !== null) {
                    $settingEntityInterface->setValue(true);
                } else {
                    $this->settingManager->insert("behavior_payment_insite", true, $shop);
                }
            }
        }
    }
}
