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

namespace PGI\Module\PGFramework\Services\Handlers;

/**
 * Class HTTPHandler
 * @package PGFramework\Services\Handlers
 */
class HTTPHandler
{
    public function isSecureConnection()
    {
        $isSecure = false;

        if ($this->getServerString('HTTPS') === 'ON') {
            $isSecure = true;
        } elseif ($this->getServerString('HTTP_X_FORWARDED_PROTO') === 'HTTPS') {
            $isSecure = true;
        } elseif ($this->getServerString('HTTP_X_FORWARDED_SSL') === 'ON') {
            $isSecure = true;
        } elseif ($this->getServerInteger('SERVER_PORT') === 443) {
            $isSecure = true;
        }

        return $isSecure;
    }

    protected function getServerString($key)
    {
        if (!array_key_exists($key, $_SERVER)) {
            return null;
        } else {
            return strtoupper($_SERVER[$key]);
        }
    }

    protected function getServerInteger($key)
    {
        if (!array_key_exists($key, $_SERVER)) {
            return null;
        } else {
            return (int) $_SERVER[$key];
        }
    }
}
