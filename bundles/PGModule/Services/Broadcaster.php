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

namespace PGI\Module\PGModule\Services;

use PGI\Module\PGFramework\Services\Handlers\RequirementHandler;
use PGI\Module\PGModule\Interfaces\EventInterface;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGSystem\Components\Parser;
use PGI\Module\PGSystem\Exceptions\Configuration as ConfigurationException;
use PGI\Module\PGSystem\Interfaces\Services\ConfigurableServiceInterface;
use PGI\Module\PGSystem\Services\Container;
use Exception;

/**
 * Interface Broadcaster
 * @package PGModule\Services
 */
class Broadcaster
{
    /** @var Container */
    private $container;

    /** @var RequirementHandler */
    private $requirementHandler;

    /** @var Parser */
    private $parser;

    /** @var Logger */
    private $logger;

    private $listeners = array();

    private static $LISTENER_DEFAULT_CONFIGURATION = array(
        'method' => 'listen',
        'priority' => 500,
        'requirements' => array(),
        'config' => array()
    );

    /**
     * Broadcaster constructor.
     * @param Container $container
     * @param Logger $logger
     * @param array $listeners
     * @throws ConfigurationException
     */
    public function __construct(
        Container $container,
        RequirementHandler $requirementHandler,
        Parser $parser,
        Logger $logger,
        array $listeners
    ) {
        $this->container = $container;
        $this->requirementHandler = $requirementHandler;
        $this->parser = $parser;
        $this->logger = $logger;

        foreach ($listeners as $listener) {
            $this->addListenerConfiguration($listener);
        }
    }

    /**
     * @param array $listenerConfiguration
     * @throws ConfigurationException
     */
    protected function addListenerConfiguration(array $listenerConfiguration)
    {
        if (!array_key_exists('event', $listenerConfiguration)) {
            $this->logger->critical("Listener declaration must contain 'event' key.", $listenerConfiguration);
            throw new ConfigurationException("Bad listener configuration.");
        } elseif (!array_key_exists('service', $listenerConfiguration)) {
            $this->logger->critical("Listener declaration must contain 'service' key.", $listenerConfiguration);
            throw new ConfigurationException("Bad listener configuration.");
        }

        $listenerConfiguration = array_merge(self::$LISTENER_DEFAULT_CONFIGURATION, $listenerConfiguration);

        if (!is_array($listenerConfiguration['event'])) {
            $listenerConfiguration['event'] = array($listenerConfiguration['event']);
        }

        $this->listeners[] = array(
            'service' => $listenerConfiguration['service'],
            'method' => $listenerConfiguration['method'],
            'events' => array_map(function ($var) {
                return strtoupper($var);
            }, $listenerConfiguration['event']),
            'priority' => $listenerConfiguration['priority'],
            'requirements' => $listenerConfiguration['requirements'],
            'config' => $listenerConfiguration['config']
        );
    }

    /**
     * @param string $serviceName
     * @param string $event
     * @param string $method
     * @param int $priority
     * @throws ConfigurationException
     */
    public function addListener(
        $serviceName,
        $event,
        $method = 'listen',
        $priority = 500,
        $requirements = array()
    ) {
        $listenerConfiguration = array(
            'service' => $serviceName,
            'method' => $method,
            'event' => $event,
            'priority' => $priority,
            'requirement' => $requirements
        );

        $this->logger->warning("Using tag to declare listeners is deprecated.", $listenerConfiguration);

        $this->addListenerConfiguration($listenerConfiguration);
    }

    /**
     * @param EventInterface $event
     * @throws Exception
     */
    public function fire(EventInterface $event)
    {
        $validListeners = array();

        foreach ($this->listeners as $listener) {
            if (in_array($event->getName(), $listener['events'])) {
                $validListeners[] = $listener;
            }
        }

        usort($validListeners, array($this, 'sortListeners'));

        foreach ($validListeners as $listener) {
            if (!$event->isPropagationStopped()) {
                $this->callListener($event, $listener);
            }
        }
    }

    /**
     * @param array $l1
     * @param array $l2
     * @return int
     */
    public function sortListeners(array $l1, array $l2)
    {
        if ($l1['priority'] < $l2['priority']) {
            return -1;
        } elseif ($l1['priority'] > $l2['priority']) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * @param EventInterface $event
     * @param array $listener
     * @throws Exception
     */
    protected function callListener(EventInterface $event, array $listener)
    {
        $serviceName = $listener['service'];
        $method = $listener['method'];

        try {
            if ($this->requirementHandler->areFulfilled($listener['requirements'])) {
                $service = $this->container->get($serviceName);

                if ($service instanceof ConfigurableServiceInterface) {
                    $service->addConfig($this->parser->parseConfig($listener['config']));
                }

                $this->logger->debug("Fire event '{$event->getName()}' to method '$method' in service '$serviceName'.");

                if (!method_exists($service, $method)) {
                    throw new Exception("Unknown listener method '$method' in service '$serviceName'.");
                }

                call_user_func(array($service, $method), $event);
            } else {
                $this->logger->notice("Event '{$event->getName()}' to method '$method' in service '$serviceName' not fired because requirements are not fulfilled.");
            }
        } catch (Exception $exception) {
            $this->logger->critical(
                "An error is occured during the execution of event '{$event->getName()}' : {$exception->getMessage()}",
                $exception
            );

            throw $exception;
        }
    }
}
