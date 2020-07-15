<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.0
 */

/**
 * Class PGFrameworkServicesHandlersSessionHandler
 * @package PGFramework\Services\Handlers
 */
class PGFrameworkServicesHandlersSessionHandler implements PGFrameworkInterfacesHandlersSessionHandlerInterface
{
    /** @var PGFrameworkServicesLogger */
    protected $logger;

    public function __construct(PGFrameworkServicesLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function get($var)
    {
        if ($this->initSession()) {
            if ($this->has($var)) {
                return $_SESSION[$var];
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function set($var, $value)
    {
        if ($this->initSession()) {
            $_SESSION[$var] = $value;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rem($var)
    {
        if ($this->initSession()) {
            if ($this->has($var)) {
                unset($_SESSION[$var]);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function raz()
    {
        if ($this->initSession()) {
            session_unset();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function has($var)
    {
        if ($this->initSession()) {
            return array_key_exists($var, $_SESSION);
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function initSession()
    {
        if (!function_exists('session_status') || !function_exists('session_start')) {
            $this->logger->error("Unavailable session functions.");
        } elseif (session_status() === PHP_SESSION_DISABLED) {
            $this->logger->error("Sessions are not available.");
        } elseif (session_status() === PHP_SESSION_NONE) {
            return session_start();
        } elseif (session_status() === PHP_SESSION_ACTIVE) {
            return true;
        }

        return false;
    }
}
