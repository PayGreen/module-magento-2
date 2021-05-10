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
 * @version   2.0.2
 *
 */

/**
 * Class PGFrameworkFoundationsSuperglobal
 * @package PGFramework\Foundations
 */
abstract class PGFrameworkFoundationsSuperglobal implements PGFrameworkInterfacesSuperglobal
{
    protected $data = array();

    public function __construct(&$superGlobal)
    {
        if (is_array($superGlobal)) {
            $this->data = &$superGlobal;
        }
    }

    public function toArray()
    {
        return $this->data;
    }

    // ###################################################################
    // ###       table access functions
    // ###################################################################

    public function offsetGet($name)
    {
        return isset($this[$name]) ? $this->data[$name] : null;
    }

    public function offsetExists($name)
    {
        return array_key_exists($name, $this->data);
    }

    public function offsetSet($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function offsetUnset($name)
    {
        if (isset($this[$name])) {
            unset($this->data[$name]);
        }
    }

    // ###################################################################
    // ###       iterator functions
    // ###################################################################

    public function rewind()
    {
        reset($this->data);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function next()
    {
        next($this->data);
    }

    public function valid()
    {
        return key($this->data) !== null;
    }
}
