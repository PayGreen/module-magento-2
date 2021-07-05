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
 * @version   2.1.0
 *
 */

/**
 * Class PGPaymentServicesListenersSetupProcessingDatabase
 * @package PGTree\Services\Listeners
 */
class PGPaymentServicesListenersSetupProcessingDatabase
{
    /** @var PGDatabaseServicesDatabaseHandler */
    private $databaseHandler;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(PGDatabaseServicesDatabaseHandler $databaseHandler, PGModuleServicesLogger $logger)
    {
        $this->databaseHandler = $databaseHandler;
        $this->logger = $logger;
    }

    /**
     * @throws Exception
     */
    public function install()
    {
        $this->logger->info("Install processing database schema.");

        $this->databaseHandler->runScript('PGPayment:processing/clean.sql');
        $this->databaseHandler->runScript('PGPayment:processing/install.sql');
    }

    /**
     * @throws Exception
     */
    public function uninstall()
    {
        $this->logger->info("Uninstall processing database schema.");

        $this->databaseHandler->runScript('PGPayment:processing/clean.sql');
    }
}
