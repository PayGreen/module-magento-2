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
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGCron\Components;

use DateTime;
use DateInterval;
use Exception;
use PGI\Module\PGSystem\Interfaces\StorageInterface;
use PGI\Module\PGCron\Interfaces\CronTabInterface;

/**
 * Class Crontab
 * @package PGFramework\Components
 */
class CronTab implements CronTabInterface
{
    /** @var StorageInterface */
    private $storage;

    /** @var array */
    private $tasks;

    /**
     * CronTab constructor.
     * @param StorageInterface $storage
     * @param array $tasks
     */
    public function __construct(StorageInterface $storage, array $tasks)
    {
        $this->storage = $storage;
        $this->tasks = $tasks;
    }

    /**
     * @inheridoc
     */
    public function has($name)
    {
        return isset($this->tasks[$name]);
    }

    /**
     * @inheridoc
     */
    public function getOutdated()
    {
        $outdated = array();
        $time = time();

        foreach ($this->tasks as $name => $interval) {
            if (!isset($this->storage[$name]) || ($this->storage[$name] < $time)) {
                $outdated[] = $name;
            }
        }

        return $outdated;
    }

    /**
     * @inheridoc
     * @throws Exception
     */
    public function refresh($name)
    {
        if (!$this->has($name)) {
            throw new Exception("Unknown task '$name' in crontab.");
        }

        $dt = new DateTime();
        $dt->add(new DateInterval($this->tasks[$name]));

        $this->storage[$name] = $dt->getTimestamp();
    }

    /**
     * @inheridoc
     */
    public function clean()
    {
        foreach($this->storage as $key => $val) {
            if (!$this->has($key)) {
                unset($this->storage[$key]);
            }
        }
    }

    /**
     * @inheridoc
     * @throws Exception
     */
    public function init()
    {
        foreach ($this->tasks as $name => $interval) {
            if (!isset($this->storage[$name])) {
                $this->refresh($name);
            }
        }
    }

    /**
     * @inheridoc
     * @throws Exception
     */
    public function toArray()
    {
        $data = array();

        foreach (array_keys($this->tasks) as $name) {
            $data[$name] = isset($this->storage[$name]) ? $this->storage[$name] : null;
        }

        return $data;
    }
}
