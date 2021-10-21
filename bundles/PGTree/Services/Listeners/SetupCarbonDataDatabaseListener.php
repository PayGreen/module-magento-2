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

namespace PGI\Module\PGTree\Services\Listeners;

use PGI\Module\PGDatabase\Services\Handlers\DatabaseHandler;
use PGI\Module\PGModule\Services\Logger;
use Exception;

/**
 * Class SetupCarbonDataDatabaseListener
 * @package PGTree\Services\Listeners
 */
class SetupCarbonDataDatabaseListener
{
    /** @var DatabaseHandler */
    private $databaseHandler;

    /** @var Logger */
    private $logger;

    public function __construct(DatabaseHandler $databaseHandler, Logger $logger)
    {
        $this->databaseHandler = $databaseHandler;
        $this->logger = $logger;
    }

    /**
     * @throws Exception
     */
    public function install()
    {
        $this->logger->info("Install PayGreen Tree database schema.");

        $this->databaseHandler->runScript('PGTree:carbon_data/clean.sql');
        $this->databaseHandler->runScript('PGTree:carbon_data/schema.sql');
    }

    /**
     * @throws Exception
     */
    public function uninstall()
    {
        $this->logger->info("Uninstall PayGreen Tree database schema.");

        $this->databaseHandler->runScript('PGTree:carbon_data/clean.sql');
    }
}
