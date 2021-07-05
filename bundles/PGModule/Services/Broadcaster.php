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
 * @version   2.1.0
 *
 */

/**
 * Interface PGModuleServicesBroadcaster
 * @package PGModule\Services
 */
class PGModuleServicesBroadcaster
{
    /** @var PGSystemServicesContainer */
    private $container;

    /** @var PGModuleServicesLogger */
    private $logger;

    private $listeners = array();

    private static $LISTENER_DEFAULT_CONFIGURATION = array(
        'method' => 'listen',
        'priority' => 500
    );

    /**
     * PGModuleServicesBroadcaster constructor.
     * @param PGSystemServicesContainer $container
     * @param PGModuleServicesLogger $logger
     * @param array $listeners
     * @throws PGSystemExceptionsConfiguration
     */
    public function __construct(
        PGSystemServicesContainer $container,
        PGModuleServicesLogger $logger,
        array $listeners
    ) {
        $this->container = $container;
        $this->logger = $logger;

        foreach ($listeners as $listener) {
            $this->addListenerConfiguration($listener);
        }
    }

    /**
     * @param array $listenerConfiguration
     * @throws PGSystemExceptionsConfiguration
     */
    protected function addListenerConfiguration(array $listenerConfiguration)
    {
        if (!array_key_exists('event', $listenerConfiguration)) {
            $this->logger->critical("Listener declaration must contain 'event' key.", $listenerConfiguration);
            throw new PGSystemExceptionsConfiguration("Bad listener configuration.");
        } elseif (!array_key_exists('service', $listenerConfiguration)) {
            $this->logger->critical("Listener declaration must contain 'service' key.", $listenerConfiguration);
            throw new PGSystemExceptionsConfiguration("Bad listener configuration.");
        }

        $listenerConfiguration = array_merge(self::$LISTENER_DEFAULT_CONFIGURATION, $listenerConfiguration);

        if (!is_array($listenerConfiguration['event'])) {
            $listenerConfiguration['event'] = array($listenerConfiguration['event']);
        }

        $this->listeners[] = array(
            'service' => $listenerConfiguration['service'],
            'method' => $listenerConfiguration['method'],
            'events' => array_map('strtoupper', $listenerConfiguration['event']),
            'priority' => $listenerConfiguration['priority']
        );
    }

    /**
     * @param string $serviceName
     * @param string $event
     * @param string $method
     * @param int $priority
     * @throws PGSystemExceptionsConfiguration
     */
    public function addListener($serviceName, $event, $method = 'listen', $priority = 500)
    {
        $listenerConfiguration = array(
            'service' => $serviceName,
            'method' => $method,
            'event' => $event,
            'priority' => $priority
        );

        $this->logger->warning("Using tag to declare listeners is deprecated.", $listenerConfiguration);

        $this->addListenerConfiguration($listenerConfiguration);
    }

    /**
     * @param PGModuleInterfacesEvent $event
     * @throws Exception
     */
    public function fire(PGModuleInterfacesEvent $event)
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
     * @param PGModuleInterfacesEvent $event
     * @param $listener
     * @throws Exception
     */
    protected function callListener(PGModuleInterfacesEvent $event, array $listener)
    {
        $serviceName = $listener['service'];
        $method = $listener['method'];
        $service = $this->container->get($serviceName);

        try {
            $this->logger->debug("Fire event '{$event->getName()}' to method '$method' in service '$serviceName'.");

            if (!method_exists($service, $method)) {
                throw new Exception("Unknown listener method '$method' in service '$serviceName'.");
            }

            call_user_func(array($service, $method), $event);
        } catch (Exception $exception) {
            $this->logger->critical(
                "An error is occured during the execution of event '{$event->getName()}' : {$exception->getMessage()}",
                $exception
            );

            throw $exception;
        }
    }
}
