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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGFramework\Foundations;

use PGI\Module\PGFramework\Components\Events\Task as TaskEventComponent;
use PGI\Module\PGFramework\Interfaces\TaskInterface;
use PGI\Module\PGModule\Services\Broadcaster;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class AbstractProcessor
 * @package PGFramework\Foundations
 */
abstract class AbstractProcessor extends AbstractObject
{
    const PROCESSOR_NAME = 'UndefinedTask';

    private $exceptions = array();

    private $steps = array();

    /** @var Broadcaster */
    protected $broadcaster;

    /**
     * @param Broadcaster $broadcaster
     */
    public function setBroadcaster(Broadcaster $broadcaster)
    {
        $this->broadcaster = $broadcaster;
    }

    /**
     * @param array $steps
     */
    public function setSteps(array $steps)
    {
        $this->steps = $steps;
    }

    public function pushStep($step)
    {
        array_push($this->steps, $step);
    }

    public function pushSteps(array $steps)
    {
        foreach ($steps as $step) {
            $this->pushStep($step);
        }
    }

    public function addStep($step)
    {
        array_unshift($this->steps, $step);
    }

    public function addSteps(array $steps)
    {
        foreach (array_reverse($steps) as $step) {
            $this->addStep($step);
        }
    }

    protected function addException(Exception $exception)
    {
        $this->exceptions[] = $exception;
    }

    /**
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * @throws Exception
     */
    public function execute(TaskInterface $task)
    {
        /** @var Logger $logger */
        $logger = $this->getService('logger');

        $taskName = static::PROCESSOR_NAME;

        if ($task->getStatus() === null) {
            $step = array_shift($this->steps);

            if ($step !== null) {
                try {
                    $parameters = array($task);

                    if (is_array($step)) {
                        list($method, $arguments) = $step;
                        $parameters = array_merge($parameters, $arguments);
                        $logger->debug("[TASK-$taskName] Running step : '$method'.");
                    } else {
                        $method = $step;
                        $logger->debug("[TASK-$taskName] Running step : '$step'.");
                    }

                    call_user_func_array(array($this, $method . 'Step'), $parameters);
                } catch (Exception $exception) {
                    $logger->error("Catched error : '{$exception->getMessage()}'.", $exception);

                    $this->addException($exception);

                    $task->setStatus($task::STATE_FATAL_ERROR);
                }

                $this->execute($task);
            }
        }
        else {
            $status = $task->getStatusName($task->getStatus());
            $event = new TaskEventComponent($taskName,$task);
            $this->broadcaster->fire($event);
            $logger->info("[TASK-$taskName] Status : '$status'.");
        }
    }

    protected function setStatusStep(TaskInterface $task, $status)
    {
        $task->setStatus($status);
    }
}
