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
 * @version   1.0.1
 */

class PGModuleServicesUpgradesDatabaseMultiShopUpgrade implements PGFrameworkInterfacesUpgradeInterface
{
    /** @var PGFrameworkServicesHandlersDatabaseHandler */
    private $databaseHandler;

    /** @var PGDomainInterfacesShopHandlerInterface */
    private $shopHandler;

    public function __construct(
        PGFrameworkServicesHandlersDatabaseHandler $databaseHandler,
        PGDomainInterfacesShopHandlerInterface $shopHandler
    ) {
        $this->databaseHandler = $databaseHandler;
        $this->shopHandler = $shopHandler;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(PGFrameworkComponentsUpgradeStage $upgradeStage)
    {
        $id_shop = $this->shopHandler->getCurrentShopPrimary();

        $this->databaseHandler->execute("ALTER TABLE `%{database.entities.button.table}` ADD `id_shop` INT(10) UNSIGNED NOT NULL DEFAULT '$id_shop';");
        $this->databaseHandler->execute("ALTER TABLE `%{database.entities.category_has_payment.table}` ADD `id_shop` INT(10) UNSIGNED NOT NULL DEFAULT '$id_shop';");

        return true;
    }
}
