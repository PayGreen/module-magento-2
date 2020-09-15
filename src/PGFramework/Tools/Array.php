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

abstract class PGFrameworkToolsArray
{
    /**
     * @param array $array
     * @return bool
     */
    public static function isSequential(array $array)
    {
        if (array() === $array) {
            return true;
        }

        return (array_keys($array) === range(0, count($array) - 1));
    }

    public static function merge(&$localData, $incomeData)
    {
        if (!is_array($localData) || !is_array($incomeData)) {
            $localData = $incomeData;
        } elseif (self::isSequential($localData) && self::isSequential($incomeData)) {
            $localData = array_merge($localData, $incomeData);
        } else {
            foreach ($incomeData as $key => $val) {
                if (substr($key, 0, 1) === '!') {
                    $key = substr($key, 1);
                    $localData[$key] = $val;
                } elseif (array_key_exists($key, $localData)) {
                    self::merge($localData[$key], $val);
                } else {
                    $localData[$key] = $val;
                }
            }
        }
    }

    public static function stripSlashes($value)
    {
        $value = is_array($value) ?
            array_map(array('PGFrameworkToolsArray', 'stripSlashes'), $value) :
            stripslashes($value);

        return $value;
    }
}
