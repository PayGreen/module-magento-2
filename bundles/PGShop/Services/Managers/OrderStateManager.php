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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGShop\Services\Managers;

use PGI\Module\PGDatabase\Foundations\AbstractManager;
use PGI\Module\PGDatabase\Interfaces\RepositoryInterface;
use PGI\Module\PGFramework\Components\StateMachine as StateMachineComponent;
use PGI\Module\PGShop\Interfaces\Entities\OrderStateEntityInterface;
use PGI\Module\PGShop\Interfaces\Repositories\OrderStateRepositoryInterface;
use PGI\Module\PGShop\Services\Factories\OrderStateMachineFactory;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;
use PGI\Module\PGSystem\Exceptions\Configuration as ConfigurationException;
use Exception;
use LogicException;

/**
 * Class OrderStateManager
 *
 * @package PGShop\Services\Managers
 * @method OrderStateRepositoryInterface getRepository()
 */
class OrderStateManager extends AbstractManager
{
    /** @var OrderStateMachineFactory */
    private $machineFactory;

    public function __construct(
        RepositoryInterface $repository,
        OrderStateMachineFactory $machineFactory
    ) {
        parent::__construct($repository);

        $this->machineFactory = $machineFactory;
    }

    /**
     * @param string $mode
     * @param string $from
     * @param string $to
     * @return bool
     * @throws Exception
     */
    public function isAllowedTransition($mode, $from, $to)
    {
        /** @var StateMachineComponent $orderStateMachine */
        $orderStateMachine = $this->machineFactory->getStateMachine($mode);

        return $orderStateMachine->isAllowedTransition($from, $to);
    }

    /**
     * @param string $mode
     * @param string $state
     * @return bool
     * @throws Exception
     */
    public function isAllowedStart($mode, $state)
    {
        $orderStateMachine = $this->machineFactory->getStateMachine($mode);

        return $orderStateMachine->isAllowedStart($state);
    }

    /**
     * @param string $state
     * @return OrderStateEntityInterface|null
     * @throws ConfigurationException
     */
    public function create($state)
    {
        /** @var ParametersComponent $parameters */
        $parameters = $this->getService('parameters');

        $definition = $parameters["order.states.$state"];

        if (!$definition) {
            throw new ConfigurationException("Code definition not found : '$state'.");
        } elseif (!is_array($definition)) {
            throw new ConfigurationException("Uncorrectly defined order state : '$state'.");
        } elseif (!array_key_exists('name', $definition)) {
            throw new ConfigurationException("Target state has no name : '$state'.");
        } elseif (!is_string($definition['name'])) {
            throw new ConfigurationException("Target state name must be a string : '$state'.");
        }

        if (!array_key_exists('create', $definition) || ($definition['create'] !== true)) {
            throw new LogicException("OrderState '$state' can not be created.");
        }

        if (array_key_exists('metadata', $definition) && is_array($definition['metadata'])) {
            $metadata = $definition['metadata'];
        } else {
            $metadata = array();
        }

        return $this->getRepository()->create($state, $definition['name'], $metadata);
    }

    /**
     * @param int $id
     * @return OrderStateEntityInterface|null
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }
}
