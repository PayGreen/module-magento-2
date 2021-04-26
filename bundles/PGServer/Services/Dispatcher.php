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
 * @version   2.0.0
 *
 */

/**
 * Class PGServerServicesDispatcher
 * @package PGServer\Services
 */
class PGServerServicesDispatcher extends PGSystemFoundationsObject
{
    const DEFAULT_ACTION = 'process';

    private $controllerNames = array();

    /** @var PGModuleServicesLogger */
    private $logger;

    /** @var PGModuleServicesBroadcaster */
    private $broadcaster;

    public function __construct(
        PGModuleServicesLogger $logger,
        PGModuleServicesBroadcaster $broadcaster
    ) {
        $this->logger = $logger;
        $this->broadcaster = $broadcaster;
    }

    public function addControllerName($serviceName, $controllerName = null)
    {
        if ($controllerName === null) {
            if (preg_match('/^controller\.(?P<name>.+)/', $serviceName, $result)) {
                $controllerName = $result['name'];
            } else {
                throw new Exception(
                    "Unable to automatically determine the controller name with the service name : '$serviceName'."
                );
            }
        }

        $this->controllerNames[$controllerName] = $serviceName;
    }

    /**
     * @param PGServerFoundationsAbstractRequest $request
     * @param string $localization
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    public function dispatch(PGServerFoundationsAbstractRequest $request, $localization)
    {
        if (!strpos($localization, '@')) {
            $action = $actionName = self::DEFAULT_ACTION;
            $controllerName = 'action.' . $localization;
            $controller = $this->getService($controllerName);
        } else {
            list($actionName, $controllerName) = explode('@', $localization, 2);

            /** @var PGServerFoundationsAbstractController $controller */
            $controller = $this->getController($controllerName);
    
            if (!empty($actionName)) {
                $action = $actionName . 'Action';
            } else {
                $action = self::DEFAULT_ACTION;
            }
        }

        $controller->setRequest($request);
        
        $class = get_class($controller);

        if (!method_exists($controller, $action)) {
            throw new Exception("Target controller '$class' has no action method '$action'.");
        }

        $event = new PGServerComponentsActionEvent($request, $controller, $controllerName, $actionName);
        $this->broadcaster->fire($event);

        $this->logger->debug("Execute method '$action' on '$class'.");

        /** @var PGServerFoundationsAbstractResponse $response */
        $response = call_user_func(array($controller, $action));

        $this->logger->debug("Response successfully built.");

        return $response;
    }

    /**
     * @param string $name
     * @return PGServerFoundationsAbstractController
     */
    protected function getController($name)
    {
        if (!array_key_exists($name, $this->controllerNames)) {
            throw new LogicException("Unknown controller name : '$name'.");
        }

        /** @var PGServerFoundationsAbstractController $controller */
        $controller = $this->getService($this->controllerNames[$name]);

        return $controller;
    }
}
