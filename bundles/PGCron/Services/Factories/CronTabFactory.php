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

namespace PGI\Module\PGCron\Services\Factories;

use PGI\Module\PGCron\Components\CronTab;
use PGI\Module\PGSystem\Interfaces\StorageInterface;

class CronTabFactory
{
    /** @var array */
    private $tasks;

    public function __construct(array $tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * @param StorageInterface $storage
     * @param string $tab
     * @return CronTabInterface
     */
    public function build(StorageInterface $storage, $tab)
    {
        $tasks = array();

        foreach ($this->tasks as $name => $task) {
            if (isset($task['tab']) && ($task['tab'] === $tab)) {
                $tasks[$name] = $task['frequency'];
            }
        }

        return new CronTab($storage, $tasks);
    }
}