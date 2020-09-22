<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.1.1
 */

/**
 * Class PGViewServicesBuildersViewBuilder
 * @package PGView\Services\Builders
 */
class PGViewServicesBuildersViewBuilder
{
    /** @var PGFrameworkContainer */
    private $container;

    private $viewNames = array();

    public function __construct(PGFrameworkContainer $container)
    {
        $this->container = $container;
    }

    public function addViewServiceName($serviceName, $viewName = null)
    {
        if ($viewName === null) {
            if (preg_match("/^view\.(?P<name>.+)/", $serviceName, $result)) {
                $viewName = $result['name'];
            } else {
                throw new Exception("Unable to automatically determine the view name with the service name : '$serviceName'.");
            }
        }

        $this->viewNames[$viewName] = $serviceName;
    }

    /**
     * @param string $name
     * @return PGViewInterfacesViewInterface
     * @throws Exception
     */
    public function build($name)
    {
        if (!array_key_exists($name, $this->viewNames)) {
            throw new LogicException("Unknown view name : '$name'.");
        }

        $serviceName = $this->viewNames[$name];

        /** @var PGViewInterfacesViewInterface $view */
        $view = $this->container->get($serviceName);

        if (! $view instanceof PGViewInterfacesViewInterface) {
            throw new Exception("Service '$serviceName' must implements PGFrameworkInterfacesViewInterface interface.");
        }

        return $view;
    }
}
