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

namespace PGI\Module\PGSystem\Components;

use PGI\Module\PGSystem\Tools\Collection as CollectionTool;
use ArrayAccess;
use Exception;

/**
 * Class Bag
 * @package PGSystem\Components
 */
class Bag implements ArrayAccess
{
    private $data = array();

    private $dotSeparator = true;

    private $bin;

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
        CollectionTool::merge($this->data, $data);
    }

    // ###################################################################
    // ###       sous-fonctions d'accès par tableau
    // ###################################################################

    public function offsetSet($var, $value)
    {
        // Thrashing unused arguments
        $this->bin = array($var, $value);

        throw new Exception('A data tree cannot be modified.');
    }
    public function offsetExists($var)
    {
        return ($this->searchData($var) !== null);
    }
    public function offsetUnset($var)
    {
        // Thrashing unused arguments
        $this->bin = $var;

        throw new Exception('A data tree cannot be modified.');
    }
    public function offsetGet($var)
    {
        return $this->get($var);
    }

    // ###################################################################
    // ###       sous-fonctions utilitaires
    // ###################################################################

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
            return null;
        }

        if (!empty($all_keys)) {
            $key = implode('.', $all_keys);
            return $this->searchData($key, $data);
        } else {
            return $data;
        }
    }
}
