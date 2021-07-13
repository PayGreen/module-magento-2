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
 * @version   2.1.1
 *
 */

/**
 * Class PGFrameworkServicesHandlersCookieHandler
 * @package PGFramework\Services\Handlers
 */
class PGFrameworkServicesHandlersCookieHandler
{
    /** @var PGFrameworkInterfacesSuperglobal */
    private $cookieAdapter;
    
    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(
        PGFrameworkInterfacesSuperglobal $cookieAdapter,
        PGModuleServicesLogger $logger
    ) {
        $this->cookieAdapter = $cookieAdapter;
        $this->logger = $logger;
    }

    /**
     * @param string $var
     * @return mixed|null
     */
    public function get($var)
    {
        if ($this->has($var)) {
            return $this->cookieAdapter[$var];
        } else {
            $this->logger->error("Cookie var not found : '$var'.");
        }

        return null;
    }

    /**
     * @param string $var
     * @return bool
     */
    public function has($var)
    {
        return isset($this->cookieAdapter[$var]);
    }

    /**
     * @param string $var
     * @return void
     */
    public function set($var, $val)
    {
        $this->cookieAdapter[$var] = $val;
    }
}
