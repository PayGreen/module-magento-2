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
 * @version   1.2.5
 *
 */

class PGModuleServicesListenersSetupDatabaseListener
{
    /** @var PGFrameworkServicesHandlersDatabaseHandler */
    private $databaseHandler;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    /**
     * PGModuleServicesListenersSetupDatabaseListener constructor.
     * @param PGFrameworkServicesHandlersDatabaseHandler $databaseHandler
     * @param PGFrameworkServicesLogger $logger
     */
    public function __construct(PGFrameworkServicesHandlersDatabaseHandler $databaseHandler, PGFrameworkServicesLogger $logger)
    {
        $this->databaseHandler = $databaseHandler;
        $this->logger = $logger;
    }

    /**
     * @throws Exception
     */
    public function install()
    {
        $this->logger->info("Install Paygreen database schema.");

        $this->databaseHandler->runScript('PGModule:clean.sql');
        $this->databaseHandler->runScript('PGModule:schema.sql');
    }

    /**
     * @throws Exception
     */
    public function uninstall()
    {
        $this->logger->info("Uninstall Paygreen database schema.");

        $this->databaseHandler->runScript('PGModule:clean.sql');
    }
}
