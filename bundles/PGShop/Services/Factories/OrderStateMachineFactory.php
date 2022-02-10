<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGShop\Services\Factories;

use PGI\Module\PGFramework\Components\StateMachine as StateMachineComponent;
use Exception;

/**
 * Class OrderStateMachineFactory
 * @package PGShop\Services\Factories
 */
class OrderStateMachineFactory
{
    private $configuration;

    /** @var StateMachineComponent[] */
    private $stateMachines = array();

    /**
     * OrderStateMachineFactory constructor.
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $name
     * @return StateMachineComponent
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

        $this->stateMachines[$name] = new StateMachineComponent($this->configuration[$name]);
    }
}
