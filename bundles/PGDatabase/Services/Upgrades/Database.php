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
 * @version   2.0.2
 *
 */

/**
 * Class PGDatabaseServicesUpgradesDatabase
 * @package PGDatabase\Services\Upgrades
 */
class PGDatabaseServicesUpgradesDatabase implements PGModuleInterfacesUpgrade
{
    /** @var PGDatabaseServicesDatabaseHandler */
    private $databaseHandler;

    public function __construct(PGDatabaseServicesDatabaseHandler $databaseHandler)
    {
        $this->databaseHandler = $databaseHandler;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(PGModuleComponentsUpgrade $upgradeStage)
    {
        $script = $upgradeStage->getConfig('script');

        if (empty($script)) {
            throw new Exception("Database upgrade require 'script' parameter.");
        }

        $scripts = is_array($script) ? $script : array($script);

        foreach ($scripts as $script) {
            $result = $this->databaseHandler->runScript($script);

            if (!$result) {
                return false;
            }
        }

        return true;
    }
}
