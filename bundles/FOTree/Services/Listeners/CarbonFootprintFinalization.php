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
 * @version   2.1.0
 *
 */

/**
 * Class FOTreeServicesListenersCarbonFootprintFinalization
 * @package FOTree\Services\Listeners
 */
class FOTreeServicesListenersCarbonFootprintFinalization
{
    /** @var PGTreeServicesHandlersTreeCarbonOffsetting */
    private $treeCarbonOffsettingHandler;

    /** @var PGFrameworkServicesHandlersRequirementHandler */
    private $requirementHandler;

    /** @var PGTreeServicesRepositoriesCarbonData */
    private $carbonDataRepository;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(
        PGTreeServicesHandlersTreeCarbonOffsetting $treeCarbonOffsettingHandler,
        PGFrameworkServicesHandlersRequirementHandler $requirementHandler,
        PGTreeServicesRepositoriesCarbonData $carbonDataRepository,
        PGModuleServicesLogger $logger
    ) {
        $this->treeCarbonOffsettingHandler = $treeCarbonOffsettingHandler;
        $this->requirementHandler = $requirementHandler;
        $this->carbonDataRepository = $carbonDataRepository;
        $this->logger = $logger;
    }

    /**
     * @params PGShopComponentsEventsOrderValidation $event
     * @throws Exception
     */
    public function listen(PGShopComponentsEventsLocalOrder $event)
    {
        try {
            if ($this->requirementHandler->isFulfilled('tree_activation', true)) {
                /** @var PGShopInterfacesEntitiesOrder $order */
                $order = $event->getOrder();

                if ($order === null) {
                    throw new Exception("Order is required to finalize carbon footprint.");
                } elseif (!$order instanceof PGShopInterfacesEntitiesOrder) {
                    throw new Exception("Order must be an instance of PGShopInterfacesEntitiesOrder.");
                }

                $orderId = $order->id();

                if ($this->carbonDataRepository->findByOrderPrimary($orderId) === null) {
                    $this->treeCarbonOffsettingHandler->computeCarbonOffsetting(
                        $order,
                        $order->getCustomer(),
                        $order->getCarrier()
                    );

                    /** @var APITreeComponentsRepliesCarbonFootprint $carbonFootprintResponse */
                    $carbonFootprintResponse = $this->treeCarbonOffsettingHandler->getCarbonOffsetting();

                    $this->treeCarbonOffsettingHandler->closeCarbonOffsetting();

                    $this->treeCarbonOffsettingHandler->saveCarbonData($order, $carbonFootprintResponse);

                    $this->logger->debug('Carbon footprint successfully finalized.');
                } else {
                    $this->logger->debug("Carbon data already exist for order #$orderId.");
                }
            }
        } catch (Exception $exception) {
            $text = "An error occured during carbon footprint finalization : " .$exception->getMessage();
            $this->logger->error($text, $exception);
        }
    }
}