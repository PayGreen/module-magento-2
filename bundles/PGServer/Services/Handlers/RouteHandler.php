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
 * @version   2.0.1
 *
 */

/**
 * Class PGServerServicesHandlersRouteHandler
 * @package PGServer\Services\Handlers
 */
class PGServerServicesHandlersRouteHandler
{
    private $routes = array();

    /** @var PGFrameworkServicesHandlersRequirementHandler */
    private $requirementHandler;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param PGFrameworkServicesHandlersRequirementHandler $requirementHandler
     */
    public function setRequirementHandler(PGFrameworkServicesHandlersRequirementHandler $requirementHandler)
    {
        $this->requirementHandler = $requirementHandler;
    }

    /**
     * @param string $name
     * @return bool
     * @throws Exception
     */
    public function areRequirementsFulfilled($name)
    {
        $config = $this->getRouteConfiguration($name);

        $requirements = $config['requirements'];

        return $requirements ? $this->requirementHandler->areFulfilled($requirements) : true;
    }

    /**
     * @param string $routeName
     * @param string $requirementName
     * @return bool
     * @throws Exception
     */
    public function isRequirementFulfilled($routeName, $requirementName)
    {
        $config = $this->getRouteConfiguration($routeName);

        $requirementConfiguration = $config["requirements.$requirementName"];

        if ($requirementConfiguration === null) {
            return true;
        } else {
            return $this->requirementHandler->isFulfilled($requirementName, $requirementConfiguration);
        }
    }

    public function has($name)
    {
        return array_key_exists($name, $this->routes);
    }

    /**
     * @param string $name
     * @return string
     * @throws Exception
     */
    public function getTarget($name)
    {
        $config = $this->getRouteConfiguration($name);

        if (!$config['target']) {
            throw new Exception("Route '$name' has no defined target.");
        }

        return $config['target'];
    }

    /**
     * @param string $name
     * @return PGSystemComponentsBag
     * @throws Exception
     */
    protected function getRouteConfiguration($name)
    {
        if (!$this->has($name)) {
            throw new Exception("Route not found : '$name'.");
        }

        return new PGSystemComponentsBag($this->routes[$name]);
    }
}
