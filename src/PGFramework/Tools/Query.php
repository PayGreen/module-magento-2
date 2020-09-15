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
 * @version   1.1.0
 */

abstract class PGFrameworkToolsQuery
{
    public static function addParameters($url, array $data)
    {
        $parsed = parse_url($url);

        if (array_key_exists('query', $parsed)) {
            parse_str($parsed['query'], $result);
            $data = array_merge($result, $data);
        }

        return self::buildUrl(
            (array_key_exists('scheme', $parsed) ? $parsed['scheme'] : null),
            (array_key_exists('user', $parsed) ? $parsed['user'] : null),
            (array_key_exists('pass', $parsed) ? $parsed['pass'] : null),
            (array_key_exists('host', $parsed) ? $parsed['host'] : null),
            (array_key_exists('port', $parsed) ? $parsed['port'] : null),
            (array_key_exists('path', $parsed) ? $parsed['path'] : null),
            $data,
            (array_key_exists('fragment', $parsed) ? $parsed['fragment'] : null)
        );
    }

    public static function buildUrl($scheme = null, $user = null, $pass = null, $host = null, $port = null, $path = null, array $data = array(), $fragment = null)
    {
        $credentials  = ($pass !== null) ? "$user:$pass" : $user;
        $port         = $port ? ":$port" : '';

        $authority    = ($credentials ? "$credentials@" : "") . $host . $port;
        $authority    = $authority ? "//$authority" : "";

        $scheme       = $scheme ? "$scheme:" : "";
        $query        = !empty($data) ? '?' . http_build_query($data) : "";
        $fragment     = $fragment ? "#$fragment" : "";

        return $scheme . $authority . $path . $query . $fragment;
    }
}
