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
 * @version   1.1.1
 */

abstract class PGFrameworkToolsVersion
{
    public static function greaterThan($v1, $v2)
    {
        return (static::compare($v1, $v2) === 1);
    }

    public static function greaterOrEqualThan($v1, $v2)
    {
        return (static::compare($v1, $v2) !== -1);
    }

    public static function lesserThan($v1, $v2)
    {
        return (static::compare($v1, $v2) === -1);
    }

    public static function lesserOrEqualThan($v1, $v2)
    {
        return (static::compare($v1, $v2) !== 1);
    }

    public static function compare($v1, $v2)
    {
        $xv1 = explode('.', $v1);
        $xv2 = explode('.', $v2);

        $max = count($xv1);
        if (count($xv2) < $max) {
            $xv2 = array_pad($xv2, $max, 0);
        } else {
            $max = count($xv2);
            $xv1 = array_pad($xv1, $max, 0);
        }

        for ($c = 0; $c < $max; $c ++) {
            $n1 = (int) $xv1[$c];
            $n2 = (int) $xv2[$c];

            if ($n1 > $n2) {
                return 1;
            } elseif ($n1 < $n2) {
                return -1;
            }
        }

        return 0;
    }
}
