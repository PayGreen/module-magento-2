<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGSystem\Tools;

/**
 * Class Collection
 * @package PGSystem\Tools
 */
abstract class Collection
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
            array_map(array('PGI\Module\PGSystem\Tools\Collection', 'stripSlashes'), $value) :
            stripslashes($value);

        return $value;
    }
}
