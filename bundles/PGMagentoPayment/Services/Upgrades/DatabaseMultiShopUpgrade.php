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
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGMagentoPayment\Services\Upgrades;

use PGI\Module\PGDatabase\Services\Handlers\DatabaseHandler;
use PGI\Module\PGModule\Components\Upgrade as UpgradeComponent;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;
use PGI\Module\PGShop\Services\Handlers\ShopHandler;
use Exception;

class DatabaseMultiShopUpgrade implements UpgradeInterface
{
    /** @var DatabaseHandler */
    private $databaseHandler;

    /** @var ShopHandler */
    private $shopHandler;

    public function __construct(
        DatabaseHandler $databaseHandler,
        ShopHandler $shopHandler
    ) {
        $this->databaseHandler = $databaseHandler;
        $this->shopHandler = $shopHandler;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(UpgradeComponent $upgradeStage)
    {
        $id_shop = $this->shopHandler->getCurrentShopPrimary();

        $this->databaseHandler->execute("ALTER TABLE `%{database.entities.button.table}` ADD `id_shop` INT(10) UNSIGNED NOT NULL DEFAULT '$id_shop';");
        $this->databaseHandler->execute("ALTER TABLE `%{database.entities.category_has_payment.table}` ADD `id_shop` INT(10) UNSIGNED NOT NULL DEFAULT '$id_shop';");

        return true;
    }
}
