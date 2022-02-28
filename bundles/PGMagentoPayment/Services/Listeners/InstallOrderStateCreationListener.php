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
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGMagentoPayment\Services\Listeners;

use PGI\Module\PGModule\Components\Events\Module as ModuleEventComponent;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGShop\Services\Managers\OrderStateManager;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;

class InstallOrderStateCreationListener
{
    /** @var OrderStateManager */
    private $orderStateManager;

    /** @var ParametersComponent */
    private $parameters;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(OrderStateManager $orderStateManager, ParametersComponent $parameters, LoggerInterface $logger)
    {
        $this->orderStateManager = $orderStateManager;
        $this->parameters = $parameters;
        $this->logger = $logger;
    }

    public function createOrderStates(ModuleEventComponent $event)
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