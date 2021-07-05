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
 * Class PGTreeServicesHandlersTreeCarbonOffsetting
 * @package PGTree\Services\Handlers
 */
class PGTreeServicesHandlersTreeCarbonOffsetting
{
    /** @var APITreeServicesApiFacade */
    private $treeAPIFacade;

    /** @var PGTreeServicesHandlersTreeFootprintId */
    private $footprintIdHandler;

    /** @var PGModuleServicesBroadcaster */
    private $broadcaster;

    /** @var PGFrameworkServicesHandlersRequirementHandler */
    private $requirementHandler;

    /** @var PGViewServicesHandlersViewHandler */
    private $viewHandler;

    /** @var PGTreeServicesManagersCarbonData */
    private $carbonDataManager;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(
        APITreeServicesApiFacade $treeAPIFacade,
        PGTreeServicesHandlersTreeFootprintId $footprintIdHandler,
        PGModuleServicesBroadcaster $broadcaster,
        PGFrameworkServicesHandlersRequirementHandler $requirementHandler,
        PGViewServicesHandlersViewHandler $viewHandler,
        PGTreeServicesManagersCarbonData $carbonDataManager,
        PGModuleServicesLogger $logger
    ) {
        $this->treeAPIFacade = $treeAPIFacade;
        $this->footprintIdHandler = $footprintIdHandler;
        $this->broadcaster = $broadcaster;
        $this->requirementHandler = $requirementHandler;
        $this->viewHandler = $viewHandler;
        $this->carbonDataManager = $carbonDataManager;
        $this->logger = $logger;
    }

    /**
     * @param PGShopInterfacesShopable|null $shopable
     * @param PGShopInterfacesEntitiesCustomer|null $customer
     * @param PGShopInterfacesEntitiesCarrier|null $carrier
     * @return PGTreeComponentsCarbonOffsettingComputing
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    public function computeCarbonOffsetting(
        PGShopInterfacesShopable $shopable = null,
        PGShopInterfacesEntitiesCustomer $customer = null,
        PGShopInterfacesEntitiesCarrier $carrier = null
    ) {
        if (!$this->requirementHandler->isFulfilled('tree_activation')) {
            throw new Exception("Compute carbon offsetting require 'tree_activation'.");
        }

        $key = $this->footprintIdHandler->getFootprintId();

        $carbonOffsettingComputing = new PGTreeComponentsCarbonOffsettingComputing($key);

        $event = new PGTreeComponentsEventsCarbonOffsettingComputing(
            $carbonOffsettingComputing,
            $shopable,
            $customer,
            $carrier
        );

        $this->broadcaster->fire($event);

        return $carbonOffsettingComputing;
    }

    /**
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    public function closeCarbonOffsetting() {
        if (!$this->requirementHandler->isFulfilled('tree_activation')) {
            throw new Exception("Compute carbon offsetting require 'tree_activation'.");
        }

        $this->logger->debug('Create carbon footprint purchase.');

        $key = $this->footprintIdHandler->getFootprintId();

        /** @var PGClientComponentsResponse $response */
        $response = $this->treeAPIFacade->createCarbonFootprintPurchase($key);

        if ($response->getHTTPCode() === 200) {
            $this->footprintIdHandler->resetFootprintId();
        }
    }

    /**
     * @return APITreeComponentsRepliesCarbonFootprint
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    public function getCarbonOffsetting() {
        if (!$this->requirementHandler->isFulfilled('tree_activation')) {
            throw new Exception("Compute carbon offsetting require 'tree_activation'.");
        }

        $this->logger->debug('Retrieve carbon footprint.');

        $key = $this->footprintIdHandler->getFootprintId();

        return $this->treeAPIFacade->getCarbonFootprintEstimation($key, true);
    }

    /**
     * @param PGShopInterfacesEntitiesOrder $order
     * @param APITreeComponentsRepliesCarbonFootprint $carbonFootprint
     * @throws Exception
     */
    public function saveCarbonData(
        PGShopInterfacesEntitiesOrder $order,
        APITreeComponentsRepliesCarbonFootprint $carbonFootprint
    ) {
        $carbonDataEntity = $this->carbonDataManager->create(
            $carbonFootprint->getFingerprint(),
            $carbonFootprint->getEstimatedCarbon(),
            $carbonFootprint->getEstimatedPrice()
        );

        $carbonDataEntity
            ->setOrderId($order->id())
            ->setUserId($order->getCustomerId())
        ;

        $this->carbonDataManager->save($carbonDataEntity);
    }
}
