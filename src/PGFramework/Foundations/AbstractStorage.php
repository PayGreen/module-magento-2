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
 * @version   1.0.1
 */

/**
 * Class PGFrameworkFoundationsAbstractStorage
 * @package PGFramework\Foundations
 */
abstract class PGFrameworkFoundationsAbstractStorage implements PGFrameworkInterfacesStorageInterface
{
    /** @var array */
    private $data = array();

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    public function offsetGet($offset)
    {
        return isset($this[$offset]) ? $this->data[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;

        $this->saveData();
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);

        $this->saveData();
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    abstract protected function loadData();

    abstract protected function saveData();
}
