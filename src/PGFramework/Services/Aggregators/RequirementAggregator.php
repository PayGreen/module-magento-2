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
 * @version   1.2.1
 *
 */

/**
 * Class PGFrameworkServicesAggregatorsRequirementAggregator
 * @package PGFramework\Services\Aggregators
 */
class PGFrameworkServicesAggregatorsRequirementAggregator
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
            if (preg_match('/^requirement\.(?P<name>.+)/', $serviceName, $result)) {
                $name = $result['name'];
            } else {
                throw new Exception("Unable to automatically determine the requirement name with the service name : '$serviceName'.");
            }
        }

        $this->serviceNames[$name] = $serviceName;
    }

    /**
     * @param string $name
     * @return PGFrameworkInterfacesRequirementInterface
     * @throws Exception
     */
    public function getRequirement($name)
    {
        if (!array_key_exists($name, $this->serviceNames)) {
            throw new LogicException("Unknown requirement name : '$name'.");
        }

        $serviceName = $this->serviceNames[$name];

        /** @var PGFrameworkInterfacesRequirementInterface $requirement */
        $requirement = $this->container->get($serviceName);

        if (!$requirement instanceof PGFrameworkInterfacesRequirementInterface) {
            $class = get_class($requirement);
            throw new Exception("Service found '$serviceName' is not a valid Requirement. Instance of '$class' found.");
        }

        return $requirement;
    }
}
