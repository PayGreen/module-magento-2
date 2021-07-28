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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGDatabase\Services\Listeners;

use PGI\Module\PGDatabase\Services\Handlers\DatabaseHandler;
use PGI\Module\PGModule\Components\Events\Module as ModuleEventComponent;

/**
 * Class GenericDatabaseRunnerListener
 * @package PGDatabase\Services\Listeners
 */
class GenericDatabaseRunnerListener
{
    /** @var DatabaseHandler */
    private $databaseHandler;

    /** @var array */
    private $scripts;

    private $bin;

    public function __construct(DatabaseHandler $databaseHandler, $scripts)
    {
        $this->databaseHandler = $databaseHandler;
        $this->scripts = is_array($scripts) ? $scripts : array($scripts);
    }

    public function listen(ModuleEventComponent $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;

        foreach ($this->scripts as $script) {
            $this->databaseHandler->runScript($script);
        }
    }
}
