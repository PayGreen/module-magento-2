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
 * @version   0.3.5
 */

/**
 * Class PGFrameworkFoundationsAbstractProcessor
 * @package PGFramework\Foundations
 */
abstract class PGFrameworkFoundationsAbstractProcessor extends PGFrameworkFoundationsAbstractObject
{
    const PROCESSOR_NAME = 'UndefinedTask';

    private $exceptions = array();

    private $steps = array();

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

    public function execute(PGFrameworkInterfacesTaskInterface $task)
    {
        /** @var PGFrameworkServicesLogger $logger */
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
        } else {
            $status = $task->getStatusName($task->getStatus());
            $logger->info("[TASK-$taskName] Status : '$status'.");
        }
    }

    protected function setStatusStep(PGFrameworkInterfacesTaskInterface $task, $status)
    {
        $task->setStatus($status);
    }
}
