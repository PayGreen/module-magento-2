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
