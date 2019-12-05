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
 * Class PGDomainServicesOrderStateMachineFactory
 * @package PGDomain\Services
 */
class PGDomainServicesOrderStateMachineFactory
{
    private $configuration;

    /** @var PGFrameworkComponentsStateMachine[] */
    private $stateMachines = array();

    /**
     * PGDomainServicesOrderStateMachineFactory constructor.
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $name
     * @return PGFrameworkComponentsStateMachine
     * @throws Exception
     */
    public function getStateMachine($name)
    {
        if (!isset($this->stateMachines[$name])) {
            $this->buildStateMachine($name);
        }

        return $this->stateMachines[$name];
    }

    /**
     * @param string $name
     * @throws Exception
     */
    public function buildStateMachine($name)
    {
        if (!array_key_exists($name, $this->configuration)) {
            throw new Exception("Order state machine definition not found : '$name'.");
        }

        $this->stateMachines[$name] = new PGFrameworkComponentsStateMachine($this->configuration[$name]);
    }
}
