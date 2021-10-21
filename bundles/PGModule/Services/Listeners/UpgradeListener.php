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

namespace PGI\Module\PGModule\Services\Listeners;

use PGI\Module\PGModule\Components\Events\Module as ModuleEventComponent;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGModule\Services\Upgrader;
use Exception;

/**
 * Class UpgradeListener
 * @package PGModule\Services\Listeners
 */
class UpgradeListener
{
    /** @var Upgrader */
    private $upgrader;

    /** @var Logger */
    private $logger;

    public function __construct(Upgrader $upgrader, Logger $logger)
    {
        $this->upgrader = $upgrader;
        $this->logger = $logger;
    }

    public function listen(ModuleEventComponent $event)
    {
        try {
            $this->upgrader->upgrade($event->getLastUpdate(), PAYGREEN_MODULE_VERSION);
        } catch (Exception $exception) {
            $this->logger->error("Error during module upgrade : " . $exception->getMessage(), $exception);
        }
    }
}
