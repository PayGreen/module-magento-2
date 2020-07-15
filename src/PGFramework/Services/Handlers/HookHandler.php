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
 * @version   1.0.0
 */

/**
 * Class PGFrameworkServicesHandlersHookHandler
 *
 * @package PGFramework\Services\Handlers
 */
class PGFrameworkServicesHandlersHookHandler
{
    /** @var PGFrameworkContainer */
    private $container;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    private $serviceNames = array();

    private $hooks = array();

    public function __construct(PGFrameworkContainer $container, PGFrameworkServicesLogger $logger)
    {
        $this->container = $container;
        $this->logger = $logger;
    }

    /**
     * @param string $serviceName
     * @param string|null $hookName
     * @throws Exception
     */
    public function addHookName($serviceName, $hookName = null)
    {
        if ($hookName === null) {
            if (preg_match('/^hook\.(?P<name>.+)/', $serviceName, $result)) {
                $hookName = $result['name'];
            } else {
                throw new Exception("Unable to automatically determine the hook name with the service name : '$serviceName'.");
            }
        }

        $this->serviceNames[$hookName] = $serviceName;
    }

    /**
     * @param string $hookName
     * @param string $methodName
     * @return callable
     */
    public function buildHook($hookName, $methodName)
    {
        $hookIdentifier = md5("$hookName-$methodName");

        $this->hooks[$hookIdentifier] = array(
            'hook' => $hookName,
            'method' => $methodName
        );

        return array($this, $hookIdentifier);
    }

    /**
     * @param string $hookIdentifier
     * @param array $arguments
     * @return mixed|void
     * @throws Exception
     */
    public function __call($hookIdentifier, array $arguments = array())
    {
        try {
            if (!isset($this->hooks[$hookIdentifier])) {
                throw new Exception("Unresolved hook or method not found : '$hookIdentifier'.");
            }

            $hookName = $this->hooks[$hookIdentifier]['hook'];
            $methodName = $this->hooks[$hookIdentifier]['method'];

            return $this->run($hookName, $methodName, $arguments);
        } catch (Exception $exception) {
            catchLowLevelException($exception, false);
        }
    }

    /**
     * @param string $hookName
     * @param string $methodName
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function run($hookName, $methodName, array $arguments = array())
    {
        $this->logger->debug("Running method '$methodName' on hook '$hookName'.");

        if (!isset($this->serviceNames[$hookName])) {
            throw new Exception("Hook not found : '$hookName'.");
        }

        $serviceName = $this->serviceNames[$hookName];

        $service = $this->container->get($serviceName);

        if (!method_exists($service, $methodName)) {
            throw new Exception("Method '$methodName' not found in hook '$serviceName'.");
        }

        return call_user_func_array(array($service, $methodName), $arguments);
    }
}
