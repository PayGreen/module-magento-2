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
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGDatabase\Services\Listeners;

use Exception;
use PGI\Module\PGDatabase\Services\Handlers\DatabaseHandler;
use PGI\Module\PGFramework\Foundations\AbstractService;
use PGI\Module\PGModule\Foundations\AbstractEvent;

/**
 * Class GenericDatabaseRunnerListener
 * @package PGDatabase\Services\Listeners
 */
class GenericDatabaseRunnerListener extends AbstractService
{
    /** @var DatabaseHandler */
    private $databaseHandler;

    private $bin;

    public function __construct(DatabaseHandler $databaseHandler)
    {
        $this->databaseHandler = $databaseHandler;
    }

    /**
     * @param AbstractEvent $event
     * @throws Exception
     */
    public function listen(AbstractEvent $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;

        $script = $this->getConfig('script');

        if (empty($script)) {
            throw new Exception("Database listener require 'script' parameter.");
        }

        $scripts = is_array($script) ? $script : array($script);

        foreach ($scripts as $script) {
            $this->databaseHandler->runScript($script);
        }
    }
}
