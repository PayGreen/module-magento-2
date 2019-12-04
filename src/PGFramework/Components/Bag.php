<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.2
 */

/**
 * Class PGFrameworkComponentsBag
 * @package PGFramework\Components
 */
class PGFrameworkComponentsBag implements arrayaccess
{
    private $data = array();

    private $dotSeparator = true;

    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    public function setDotSeparator($dotSeparator)
    {
        $this->dotSeparator = (bool) $dotSeparator;
    }

    // ###################################################################
    // ###       fonctions publiques
    // ###################################################################

    public function get($adresse)
    {
        return $this->searchData($adresse);
    }

    public function toArray()
    {
        return $this->data;
    }

    public function merge(array $data)
    {
        $this->mergeData($this->data, $data);
    }

    // ###################################################################
    // ###       sous-fonctions d'accès par tableau
    // ###################################################################

    public function offsetSet($var, $value)
    {
        throw new Exception('Un arbre de donnée ne peut être modifié.');
    }
    public function offsetExists($var)
    {
        return ($this->searchData($var) !== false);
    }
    public function offsetUnset($var)
    {
        throw new Exception('Un arbre de donnée ne peut être modifié.');
    }
    public function offsetGet($var)
    {
        return $this->get($var);
    }

    // ###################################################################
    // ###       sous-fonctions utilitaires
    // ###################################################################

    private function mergeData(&$localData, $incomeData)
    {
        if (!is_array($localData) || !is_array($incomeData)) {
            $localData = $incomeData;
        } elseif (PGFrameworkToolsArray::isSequential($localData) && PGFrameworkToolsArray::isSequential($incomeData)) {
            $localData = array_merge($localData, $incomeData);
        } else {
            foreach ($incomeData as $key => $val) {
                if (substr($key, 0, 1) === '!') {
                    $key = substr($key, 1);
                    $localData[$key] = $val;
                } elseif (array_key_exists($key, $localData)) {
                    $this->mergeData($localData[$key], $val);
                } else {
                    $localData[$key] = $val;
                }
            }
        }
    }

    private function searchData($key = false, &$data = false)
    {
        if (!$data) {
            $data =& $this->data;
        }

        if ($key === false) {
            return $data;
        }

        if ($this->dotSeparator) {
            $all_keys = explode('.', $key);
            $first_key = array_shift($all_keys);
        } else {
            $all_keys = array();
            $first_key = $key;
        }

        if (is_array($data) and isset($data[$first_key])) {
            $data =& $data[$first_key];
        } else {
            return false;
        }

        if (!empty($all_keys)) {
            $key = implode('.', $all_keys);
            return $this->searchData($key, $data);
        } else {
            return $data;
        }
    }
}
