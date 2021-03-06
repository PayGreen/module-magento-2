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

/**
 * Class PGFrameworkServicesHandlersCookieHandler
 * @package PGFramework\Services\Handlers
 */
class PGFrameworkServicesHandlersCookieHandler
{
    /** @var PGFrameworkServicesLogger */
    private $logger;

    public function __construct(PGFrameworkServicesLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $var
     * @return mixed|null
     */
    public function get($var)
    {
        if ($this->has($var)) {
            return $_COOKIE[$var];
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
        return array_key_exists($var, $_COOKIE);
    }

    /**
     * @param string $var
     * @return bool
     */
    public function set($var, $val)
    {
        return setcookie($var, $val);
    }
}
