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
 * @version   1.2.2
 *
 */

class PGServerServicesAggregatorsDeflectorAggregator
{
    /** @var PGFrameworkContainer */
    private $container;

    private $serviceNames = array();

    public function __construct(PGFrameworkContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $serviceName
     * @param string|null $name
     * @throws Exception
     */
    public function addServiceName($serviceName, $name = null)
    {
        if ($name === null) {
            if (preg_match('/^deflector\.(?P<name>.+)/', $serviceName, $result)) {
                $name = $result['name'];
            } else {
                throw new Exception("Unable to automatically determine the deflector name with the service name : '$serviceName'.");
            }
        }

        $this->serviceNames[$name] = $serviceName;
    }

    /**
     * @param string $name
     * @return PGServerInterfacesDeflectorInterface
     * @throws Exception
     */
    public function getDeflector($name)
    {
        if (!array_key_exists($name, $this->serviceNames)) {
            throw new LogicException("Unknown deflector name : '$name'.");
        }

        $serviceName = $this->serviceNames[$name];

        /** @var PGServerInterfacesDeflectorInterface $deflector */
        $deflector = $this->container->get($serviceName);

        if (!$deflector instanceof PGServerInterfacesDeflectorInterface) {
            $class = get_class($deflector);
            throw new Exception("Service '$serviceName' is not a valid deflector. Instance of '$class' found.");
        }

        return $deflector;
    }
}
