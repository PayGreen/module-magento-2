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
 * @version   1.1.0
 */

/**
 * Class PGServerServicesLinker
 * @package PGServer\Services\Handlers
 */
class PGServerServicesFactoriesLinkerFactory extends PGFrameworkFoundationsAbstractObject
{
    /** @var PGFrameworkContainer */
    private $container;

    private $localLinkerNames = array();

    public function __construct(PGFrameworkContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $serviceName
     * @param string|null $linkerName
     * @throws Exception
     */
    public function declareLocalLinker($serviceName, $linkerName = null)
    {
        if ($linkerName === null) {
            if (preg_match('/^linker\.(?P<name>.+)/', $serviceName, $result)) {
                $linkerName = $result['name'];
            } else {
                throw new Exception("Unable to automatically determine the linker name with the service name : '$serviceName'.");
            }
        }

        $this->localLinkerNames[$linkerName] = $serviceName;
    }

    /**
     * @param string $name
     * @return PGServerInterfacesLinkerInterface
     * @throws Exception
     */
    public function getLocalLinker($name)
    {
        if (!array_key_exists($name, $this->localLinkerNames)) {
            throw new Exception("Linker not found : '$name'.");
        }

        $serviceName = $this->localLinkerNames[$name];

        return $this->container->get($serviceName);
    }
}
