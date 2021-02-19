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
 * @version   1.2.3
 *
 */

abstract class PGDomainToolsPrice
{
    /**
     * @param float $value
     * @return int
     */
    public static function toInteger($value)
    {
        return (int) round($value * 100);
    }

    /**
     * @param int $value
     * @return float
     */
    public static function toFloat($value)
    {
        return (float) round($value / 100, 2);
    }

    /**
     * @param float $value
     * @return float
     */
    public static function fixFloat($value)
    {
        return (float) round($value, 2);
    }
}
