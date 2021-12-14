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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGView\Services\Listeners;

use PGI\Module\PGModule\Components\Events\Module as ModuleEventComponent;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGView\Services\Handlers\SmartyHandler;
use Exception;
use Smarty;

/**
 * Class ClearSmartyCacheListener
 * @package PGView\Services\Listeners
 */
class ClearSmartyCacheListener
{
    /** @var SmartyHandler */
    private $smartyHandler;

    /** @var Logger */
    private $logger;

    private $bin;

    public function __construct(SmartyHandler $smartyHandler, Logger $logger)
    {
        $this->smartyHandler = $smartyHandler;
        $this->logger = $logger;
    }

    public function listen(ModuleEventComponent $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;

        try {
            $this->smartyHandler->getSmarty()->clearCompiledTemplate();
            $this->logger->info("Smarty cache successfully cleared.");
        } catch (Exception $exception) {
            $this->logger->error("Error when clearing Smarty cache : " . $exception->getMessage(), $exception);
        }
    }
}
