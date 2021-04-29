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
 * @version   2.0.1
 *
 */

/**
 * Class PGFrameworkServicesSuperglobalsSession
 * @package PGFramework\Services\Superglobals
 */
class PGFrameworkServicesSuperglobalsSession implements PGFrameworkInterfacesSuperglobal
{
    protected $data = array();

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(PGModuleServicesLogger $logger)
    {
        $this->logger = $logger;
    }

    public function toArray()
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    protected function isSessionInit()
    {
        if (!function_exists('session_status') || !function_exists('session_start')) {
            $this->logger->error("Unavailable session functions.");
        } elseif (call_user_func('session_status') === constant('PHP_SESSION_DISABLED')) {
            $this->logger->error("Sessions are not available.");
        } elseif (call_user_func('session_status') === constant('PHP_SESSION_ACTIVE')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function checkSession()
    {
        $result = true;

        if (!$this->isSessionInit()) {
            $result = session_start();
        }

        if (isset($_SESSION) && is_array($_SESSION)) {
            $this->data = &$_SESSION;
        } else {
            $this->logger->alert("Session superglobal is unavailable.");
        }

        return $result;
    }

    // ###################################################################
    // ###       table access functions
    // ###################################################################

    public function offsetGet($name)
    {
        if ($this->checkSession()) {
            return isset($this[$name]) ? $this->data[$name] : null;
        }

        return null;
    }

    public function offsetExists($name)
    {
        if ($this->checkSession()) {
            return array_key_exists($name, $this->data);
        }

        return false;
    }

    public function offsetSet($name, $value)
    {
        if ($this->checkSession()) {
            $this->data[$name] = $value;
        }
    }

    public function offsetUnset($name)
    {
        if ($this->checkSession()) {
            if (isset($this[$name])) {
                unset($this->data[$name]);
            }
        }
    }

    // ###################################################################
    // ###       iterator functions
    // ###################################################################

    public function rewind()
    {
        if ($this->checkSession()) {
            reset($this->data);
        }
    }

    public function current()
    {
        if ($this->checkSession()) {
            return current($this->data);
        }

        return false;
    }

    public function key()
    {
        if ($this->checkSession()) {
            return key($this->data);
        }
      
        return false;
    }

    public function next()
    {
        if ($this->checkSession()) {
            next($this->data);
        }
    }

    public function valid()
    {
        if ($this->checkSession()) {
            return key($this->data) !== null;
        }

        return false;
    }
}
