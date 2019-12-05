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

class PGFrameworkServicesDispatcher extends PGFrameworkFoundationsAbstractObject
{
    private $controllerNames = array();

    public function addControllerName($serviceName, $controllerName)
    {
        $this->controllerNames[$controllerName] = $serviceName;
    }

    public function dispatch(PGFrameworkComponentsIncomingRequest $request, $localization)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $logger->notice("Execute controller action : $localization.");

        list($actionName, $controllerName) = explode('@', $localization, 2);

        $controller = $this->getController($controllerName);
        $action = $actionName . 'Action';

        /** @var mixed $response */
        $response = call_user_func(array($controller, $action), $request);

        return $response;
    }

    /**
     * @param string $name
     * @return PGFrameworkFoundationsAbstractController
     */
    protected function getController($name)
    {
        if (!array_key_exists($name, $this->controllerNames)) {
            throw new LogicException("Unknown controller name : '$name'.");
        }

        /** @var PGFrameworkFoundationsAbstractController $controller */
        $controller = $this->getService($this->controllerNames[$name]);

        return $controller;
    }
}
