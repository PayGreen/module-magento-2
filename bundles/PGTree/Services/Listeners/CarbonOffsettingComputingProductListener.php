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

namespace PGI\Module\PGTree\Services\Listeners;

use PGI\Module\APITree\Services\Facades\ApiFacade;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGShop\Interfaces\ShopableInterface;
use PGI\Module\PGTree\Components\Events\CarbonOffsettingComputing as CarbonOffsettingComputingEventComponent;
use Exception;

/**
 * Class CarbonOffsettingComputingProductListener
 * @package PGTree\Services\Listeners
 */
class CarbonOffsettingComputingProductListener
{
    /** @var ApiFacade */
    private $treeAPIFacade;

    /** @var Logger */
    private $logger;

    public function __construct(
        ApiFacade $treeAPIFacade,
        Logger $logger
    ) {
        $this->treeAPIFacade = $treeAPIFacade;
        $this->logger = $logger;
    }

    /**
     * @param CarbonOffsettingComputingEventComponent $event
     * @throws Exception
     */
    public function listen(CarbonOffsettingComputingEventComponent $event)
    {
        try {
            $this->treeAPIFacade->deleteProductCarbonEmission(
            $event->getCarbonOffsettingComputing()->getFingerPrintPrimary());
        } catch (Exception $exception) {
            $this->logger->warning("Unable to delete product carbon emission : " . $exception->getMessage());
        }

        /** @var ShopableInterface $shopable */
        $shopable = $event->getShopable();

        if ($shopable === null) {
            $this->logger->debug("Missing cart to compute 'Product' footprint.");
            return;
        }

        foreach ($shopable->getItems() as $item) {
            $item_quantity = $item->getQuantity();
            $item_reference = $item->getProduct()->getReference();

            try {
                $this->treeAPIFacade->addProductCarbonEmission(
                    $event->getCarbonOffsettingComputing()->getFingerPrintPrimary(),
                    $item_reference,
                    $item_quantity
                );

                $this->logger->notice('Successfully set product footprint : '.$item_reference);
            } catch (Exception $exception) {
                $this->logger->error("Unable to add carbon emission for product '$item_reference' : " . $exception->getMessage());
            }
        }
    }
}