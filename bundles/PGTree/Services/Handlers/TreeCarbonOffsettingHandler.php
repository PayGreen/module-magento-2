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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGTree\Services\Handlers;

use PGI\Module\APITree\Components\Replies\CarbonFootprint as CarbonFootprintReplyComponent;
use PGI\Module\APITree\Services\Facades\ApiFacade;
use PGI\Module\PGClient\Components\Response as ResponseComponent;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGFramework\Services\Handlers\RequirementHandler;
use PGI\Module\PGModule\Services\Broadcaster;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGShop\Interfaces\Entities\CarrierEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\CustomerEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Interfaces\ShopableInterface;
use PGI\Module\PGTree\Components\CarbonOffsettingComputing as CarbonOffsettingComputingComponent;
use PGI\Module\PGTree\Components\Events\CarbonOffsettingComputing as CarbonOffsettingComputingEventComponent;
use PGI\Module\PGTree\Services\Managers\CarbonDataManager;
use PGI\Module\PGView\Services\Handlers\ViewHandler;
use Exception;

/**
 * Class TreeCarbonOffsettingHandler
 * @package PGTree\Services\Handlers
 */
class TreeCarbonOffsettingHandler
{
    /** @var ApiFacade */
    private $treeAPIFacade;

    /** @var TreeFootprintIdHandler */
    private $footprintIdHandler;

    /** @var Broadcaster */
    private $broadcaster;

    /** @var RequirementHandler */
    private $requirementHandler;

    /** @var CarbonDataManager */
    private $carbonDataManager;

    /** @var Logger */
    private $logger;

    public function __construct(
        ApiFacade $treeAPIFacade,
        TreeFootprintIdHandler $footprintIdHandler,
        Broadcaster $broadcaster,
        RequirementHandler $requirementHandler,
        CarbonDataManager $carbonDataManager,
        Logger $logger
    ) {
        $this->treeAPIFacade = $treeAPIFacade;
        $this->footprintIdHandler = $footprintIdHandler;
        $this->broadcaster = $broadcaster;
        $this->requirementHandler = $requirementHandler;
        $this->carbonDataManager = $carbonDataManager;
        $this->logger = $logger;
    }

    /**
     * @param ShopableInterface|null $shopable
     * @param CustomerEntityInterface|null $customer
     * @param CarrierEntityInterface|null $carrier
     * @return CarbonOffsettingComputingComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function computeCarbonOffsetting(
        ShopableInterface $shopable = null,
        CustomerEntityInterface $customer = null,
        CarrierEntityInterface $carrier = null
    ) {
        if (!$this->requirementHandler->isFulfilled('tree_activation')) {
            throw new Exception("Compute carbon offsetting require 'tree_activation'.");
        }

        $key = $this->footprintIdHandler->getFootprintId();

        $carbonOffsettingComputing = new CarbonOffsettingComputingComponent($key);

        $event = new CarbonOffsettingComputingEventComponent(
            $carbonOffsettingComputing,
            $shopable,
            $customer,
            $carrier
        );

        $this->broadcaster->fire($event);

        return $carbonOffsettingComputing;
    }

    /**
     * @throws ResponseException
     * @throws Exception
     */
    public function closeCarbonOffsetting()
    {
        if (!$this->requirementHandler->isFulfilled('tree_activation')) {
            throw new Exception("Compute carbon offsetting require 'tree_activation'.");
        }

        $this->logger->debug('Create carbon footprint purchase.');

        $key = $this->footprintIdHandler->getFootprintId();

        /** @var ResponseComponent $response */
        $response = $this->treeAPIFacade->createCarbonFootprintPurchase($key);

        if ($response->getHTTPCode() === 200) {
            $this->footprintIdHandler->resetFootprintId();
        }
    }

    /**
     * @return CarbonFootprintReplyComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getCarbonOffsetting()
    {
        if (!$this->requirementHandler->isFulfilled('tree_activation')) {
            throw new Exception("Compute carbon offsetting require 'tree_activation'.");
        }

        $this->logger->debug('Retrieve carbon footprint.');

        $key = $this->footprintIdHandler->getFootprintId();

        return $this->treeAPIFacade->getCarbonFootprintEstimation($key, true);
    }

    /**
     * @param OrderEntityInterface $order
     * @param CarbonFootprintReplyComponent $carbonFootprint
     * @throws Exception
     */
    public function saveCarbonData(
        OrderEntityInterface $order,
        CarbonFootprintReplyComponent $carbonFootprint
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
