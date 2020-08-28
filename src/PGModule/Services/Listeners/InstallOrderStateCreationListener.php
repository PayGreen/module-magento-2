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
 * @version   1.0.1
 */


class PGModuleServicesListenersInstallOrderStateCreationListener
{
    /** @var PGDomainServicesManagersOrderStateManager */
    private $orderStateManager;

    /** @var PGFrameworkComponentsParameters */
    private $parameters;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    public function __construct(PGDomainServicesManagersOrderStateManager $orderStateManager, PGFrameworkComponentsParameters $parameters, PGFrameworkServicesLogger $logger)
    {
        $this->orderStateManager = $orderStateManager;
        $this->parameters = $parameters;
        $this->logger = $logger;
    }

    public function createOrderStates(PGFrameworkComponentsEventsModuleEvent $event)
    {
        $orderStateDefinitions = $this->parameters['order.states'];

        foreach ($orderStateDefinitions as $state => $orderStateDefinition) {
            if (array_key_exists('create', $orderStateDefinition) && $orderStateDefinition['create']) {
                $this->orderStateManager->create($state);
            }
        }

        $this->logger->info("OrderStates created successfully.");
    }
}