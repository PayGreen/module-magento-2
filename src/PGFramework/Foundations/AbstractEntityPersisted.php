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

/**
 * Class PGFrameworkFoundationsAbstractEntityPersisted
 * @package PGFramework\Foundations
 */
abstract class PGFrameworkFoundationsAbstractEntityPersisted extends PGFrameworkFoundationsAbstractEntity
{
    /** @var array */
    private $data;

    /**
     * PGFrameworkFoundationsAbstractEntityArray constructor.
     * @param $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function id()
    {
        return $this->get('id');
    }

    public function setPrimary($id)
    {
        if ($this->id()) {
            $class = get_class($this);
            throw new Exception("Entity already identified : $class#{$this->id()}.");
        }

        $this->data['id'] = $id;

        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function get($key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed $val
     * @return self
     */
    protected function set($key, $val)
    {
        $this->data[$key] = $val;

        return $this;
    }
}
