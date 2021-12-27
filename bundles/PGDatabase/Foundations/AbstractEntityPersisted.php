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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGDatabase\Foundations;

use PGI\Module\PGDatabase\Foundations\AbstractEntity;
use PGI\Module\PGDatabase\Foundations\AbstractEntityArray;
use Exception;

/**
 * Class AbstractEntityPersisted
 * @package PGDatabase\Foundations
 */
abstract class AbstractEntityPersisted extends AbstractEntity
{
    /** @var array */
    private $data;

    /**
     * AbstractEntityArray constructor.
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
