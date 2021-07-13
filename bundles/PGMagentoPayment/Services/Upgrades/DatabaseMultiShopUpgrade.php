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
 * @version   2.1.1
 *
 */

class PGMagentoPaymentServicesUpgradesDatabaseMultiShopUpgrade implements PGModuleInterfacesUpgrade
{
    /** @var PGDatabaseServicesDatabaseHandler */
    private $databaseHandler;

    /** @var PGShopInterfacesShopHandler */
    private $shopHandler;

    public function __construct(
        PGDatabaseServicesDatabaseHandler $databaseHandler,
        PGShopInterfacesShopHandler $shopHandler
    ) {
        $this->databaseHandler = $databaseHandler;
        $this->shopHandler = $shopHandler;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(PGModuleComponentsUpgrade $upgradeStage)
    {
        $id_shop = $this->shopHandler->getCurrentShopPrimary();

        $this->databaseHandler->execute("ALTER TABLE `%{database.entities.button.table}` ADD `id_shop` INT(10) UNSIGNED NOT NULL DEFAULT '$id_shop';");
        $this->databaseHandler->execute("ALTER TABLE `%{database.entities.category_has_payment.table}` ADD `id_shop` INT(10) UNSIGNED NOT NULL DEFAULT '$id_shop';");

        return true;
    }
}
