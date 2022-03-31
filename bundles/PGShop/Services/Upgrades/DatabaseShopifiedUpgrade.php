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

namespace PGI\Module\PGShop\Services\Upgrades;

use PGI\Module\PGDatabase\Services\Handlers\DatabaseHandler;
use PGI\Module\PGModule\Components\UpgradeStage as UpgradeComponent;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;
use PGI\Module\PGShop\Services\Handlers\ShopHandler;
use Exception;

class DatabaseShopifiedUpgrade implements UpgradeInterface
{
    /** @var DatabaseHandler */
    private $databaseHandler;

    /** @var ShopHandler */
    private $shopHandler;

    private $bin;

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
        $scripts = $upgradeStage->getConfig('scripts');

        foreach ($scripts as $script) {
            $this->databaseHandler->runScript($script, array(
                'id_shop' => $this->shopHandler->getCurrentShopPrimary()
            ));
        }
    }
}
