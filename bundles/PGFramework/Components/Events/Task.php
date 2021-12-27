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

namespace PGI\Module\PGFramework\Components\Events;

use PGI\Module\PGFramework\Interfaces\TaskInterface;
use PGI\Module\PGModule\Foundations\AbstractEvent;

/**
 * Class Task
 * @package PGFramework\Components\Events
 */
class Task extends AbstractEvent
{
    /** @var TaskInterface */
    private $task;

    /** @var string */
    private $name;

    public function __construct(
        $name,
        TaskInterface $task
    ) {
        $this->name = 'TASK.' . strtoupper($name) . '.' . $task->getStatusName($task->getStatus());
        $this->task = $task;
    }

    /**
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }

    public function getName()
    {
        return $this->name;
    }
}
