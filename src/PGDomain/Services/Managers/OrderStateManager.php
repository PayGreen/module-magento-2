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
 */

/**
 * Class PGDomainServicesManagersOrderStateManager
 *
 * @package PGDomain\Services\Managers
 * @method PGDomainInterfacesRepositoriesOrderStateRepositoryInterface getRepository()
 */
class PGDomainServicesManagersOrderStateManager extends PGFrameworkFoundationsAbstractManager
{
    /** @var PGDomainServicesOrderStateMachineFactory */
    private $machineFactory;

    public function __construct(
        PGFrameworkInterfacesRepositoryInterface $repository,
        PGDomainServicesOrderStateMachineFactory $machineFactory
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
        /** @var PGFrameworkComponentsStateMachine $orderStateMachine */
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
     * @return PGDomainInterfacesEntitiesOrderStateInterface|null
     * @throws PGFrameworkExceptionsConfigurationException
     */
    public function create($state)
    {
        /** @var PGFrameworkComponentsParameters $parameters */
        $parameters = $this->getService('parameters');

        $definition = $parameters["order.states.$state"];

        if (!$definition) {
            throw new PGFrameworkExceptionsConfigurationException("Code definition not found : '$state'.");
        } elseif (!is_array($definition)) {
            throw new PGFrameworkExceptionsConfigurationException("Uncorrectly defined order state : '$state'.");
        } elseif (!array_key_exists('name', $definition)) {
            throw new PGFrameworkExceptionsConfigurationException("Target state has no name : '$state'.");
        } elseif (!is_string($definition['name'])) {
            throw new PGFrameworkExceptionsConfigurationException("Target state name must be a string : '$state'.");
        } elseif (!array_key_exists('metadata', $definition)) {
            throw new PGFrameworkExceptionsConfigurationException("Target state has no metadata : '$state'.");
        } elseif (!is_array($definition['metadata'])) {
            throw new PGFrameworkExceptionsConfigurationException("Target state metadata must be an array : '$state'.");
        }

        if (!array_key_exists('create', $definition) || ($definition['create'] !== true)) {
            throw new LogicException("OrderState '$state' can not be created.");
        }

        return $this->getRepository()->create($state, $definition['name'], $definition['metadata']);
    }

    /**
     * @param int $id
     * @return PGDomainInterfacesEntitiesOrderStateInterface|null
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }
}
