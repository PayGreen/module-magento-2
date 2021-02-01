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
 * @version   1.2.1
 *
 */

/**
 * Class PGFrameworkServicesHandlersHTTPHandler
 * @package PGFramework\Services\Handlers
 */
class PGFrameworkServicesHandlersHTTPHandler
{
    public function isSecureConnection()
    {
        $isSecure = false;

        if (array_key_exists('HTTPS', $_SERVER) && (strtoupper($_SERVER['HTTPS']) === 'ON')) {
            $isSecure = true;
        }
        elseif (array_key_exists('HTTP_X_FORWARDED_PROTO', $_SERVER) && (strtoupper($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'HTTPS')) {
            $isSecure = true;
        }
        elseif (array_key_exists('HTTP_X_FORWARDED_SSL', $_SERVER) && (strtoupper($_SERVER['HTTP_X_FORWARDED_SSL']) === 'ON')) {
            $isSecure = true;
        }
        elseif (array_key_exists('SERVER_PORT', $_SERVER) && (intval($_SERVER['SERVER_PORT']) === 443)) {
            $isSecure = true;
        }

        return $isSecure;
    }
}
