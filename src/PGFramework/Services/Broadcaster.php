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
 * @version   0.3.4
 */

/**
 * Interface PGFrameworkServicesBroadcaster
 * @package PGFramework\Services
 */
class PGFrameworkServicesBroadcaster extends PGFrameworkFoundationsAbstractObject
{
    private $listeners = array();

    /**
     * @param string $serviceName
     * @param string $event
     * @param string $method
     * @param int $priority
     */
    public function addListener($serviceName, $event, $method = 'listen', $priority = 500)
    {
        $watchedEvents = is_array($event) ? array_map('strtoupper', $event) : array(strtoupper($event));

        $this->listeners[] = array(
            'serviceName' => $serviceName,
            'method' => $method,
            'events' => $watchedEvents,
            'priority' => (int) $priority
        );
    }

    /**
     * @param PGFrameworkInterfacesEventInterface $event
     * @throws Exception
     */
    public function fire(PGFrameworkInterfacesEventInterface $event)
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

    public function sortListeners($l1, $l2)
    {
        if ($l1['priority'] < $l2['priority']) {
            return -1;
        } elseif ($l1['priority'] > $l2['priority']) {
            return 1;
        } else {
            return 0;
        }
    }

    protected function callListener(PGFrameworkInterfacesEventInterface $event, $listener)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $service = $this->getService($listener['serviceName']);

        try {
            $logger->debug("Fire event '{$event->getName()}' to method '{$listener['method']}' in service '{$listener['serviceName']}'.");

            if (!method_exists($service, $listener['method'])) {
                throw new Exception("Unknown listener method '{$listener['method']}' in service '{$listener['serviceName']}'.");
            }

            call_user_func(array($service, $listener['method']), $event);
        } catch (Exception $exception) {
            $logger->critical("An error is occured during the execution of event '{$event->getName()}' : {$exception->getMessage()}", $exception);

            throw $exception;
        }
    }
}
