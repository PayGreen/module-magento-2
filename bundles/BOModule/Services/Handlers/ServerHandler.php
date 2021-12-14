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

namespace PGI\Module\BOModule\Services\Handlers;

use Exception;
use PGI\Module\PGModule\Services\Settings;

/**
 * Class ServerHandler
 * @package BOModule\Services\Handlers
 */
class ServerHandler
{
    private $servers = array();

    /** @var Settings */
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param string $service
     * @param string $server
     * @throws Exception
     */
    public function addServer($service, $server)
    {
        $serverName = $this->settings->get($server);

        $serverCompleteName = 'data.'.$server.'.values.'.$serverName;

        $this->servers[$service] = $serverCompleteName;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        return $this->servers;
    }
}
