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
 * @version   2.0.2
 *
 */

/**
 * Class PGFrameworkComponentsAggregator
 * @package PGSystem\Components
 */
class PGFrameworkComponentsAggregator implements PGSystemInterfacesServicesConfigurable
{
    /** @var PGSystemServicesContainer */
    private $container;

    private $index = array();

    /** @var PGSystemComponentsBag */
    private $config;

    public function __construct(PGSystemServicesContainer $container)
    {
        $this->container = $container;

        $this->setConfig(array());
    }

    public function setConfig(array $config)
    {
        $this->config = new PGSystemComponentsBag($config);
    }

    public function addConfig(array $config)
    {
        $this->config->merge($config);
    }

    public function hasConfig($key)
    {
        return isset($this->config[$key]);
    }

    public function getConfig($key)
    {
        return $this->config[$key];
    }

    /**
     * @param string $serviceName
     * @param string|null $name
     * @throws Exception
     */
    public function addServiceName($serviceName, $name = null)
    {
        $type = $this->config['type'];

        if ($name === null) {
            if (preg_match("/^$type\\.(?P<name>.+)/", $serviceName, $result)) {
                $name = $result['name'];
            } else {
                throw new Exception(
                    "Unable to automatically determine the $type name with the service name : '$serviceName'."
                );
            }
        }

        $this->index[$name] = $serviceName;
    }

    public function has($name)
    {
        return array_key_exists($name, $this->index);
    }

    /**
     * @param string $name
     * @return object
     * @throws Exception
     */
    public function getService($name)
    {
        $interface = $this->config['interface'];

        $serviceName = $this->getServiceName($name);

        /** @var object $service */
        $service = $this->container->get($serviceName);

        if ($interface) {
            if (!$service instanceof $interface) {
                $class = get_class($service);
                $text = "Service '$serviceName' is not a valid {$this->config['type']}.";
                $text .= " '$interface' is required.";
                $text .= " Instance of '$class' found.";
                throw new Exception($text);
            }
        }

        return $service;
    }

    public function getServiceName($name)
    {
        if (!$this->has($name)) {
            throw new LogicException("Unknown {$this->config['type']} name : '$name'.");
        }

        return $this->index[$name];
    }
}
