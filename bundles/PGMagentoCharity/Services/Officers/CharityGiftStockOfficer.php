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

namespace PGI\Module\PGMagentoCharity\Services\Officers;

use Exception;
use Magento\Catalog\Model\Product as LocalProduct;
use Magento\CatalogInventory\Api\StockRegistryInterface as LocalStockRegistry;
use Magento\CatalogInventory\Api\Data\StockItemInterface as LocalStockItem;
use PGI\Module\PGFramework\Foundations\AbstractService;
use PGI\Module\PGShop\Interfaces\Entities\ProductEntityInterface;

/**
 * Class CharityGiftStockOfficer
 * @package PGMagentoCharity\Services\Officers
 */
class CharityGiftStockOfficer extends AbstractService
{
    /** @var LocalStockRegistry */
    private $localStockRegistry;

    public function __construct(
        LocalStockRegistry $localStockRegistry
    ) {
        $this->localStockRegistry = $localStockRegistry;
    }

    /**
     * @param ProductEntityInterface $gift
     * @return bool
     */
    public function isValid(ProductEntityInterface $gift)
    {
        $reference = $gift->getReference();

        /** @var LocalStockItem $stockItem */
        $stockItem = $this->localStockRegistry->getStockItemBySku($reference);

        $testMaxSaleQuantity = ((int) $stockItem->getMaxSaleQty() === 1);
        $testManageStock = !$stockItem->getManageStock();

        if (!$testMaxSaleQuantity) {
            $this->log()->warning("Charity Gift #{$gift->id()} as max sale quantity greater than 1. (={$stockItem->getMaxSaleQty()})");
        }

        if (!$testManageStock) {
            $this->log()->warning("Charity Gift #{$gift->id()} as stock management enabled.");
        }

        return ($testMaxSaleQuantity && $testManageStock);
    }

    /**
     * @inheritDoc
     */
    public function validate(ProductEntityInterface $gift)
    {
        /** @var LocalProduct $localProduct */
        $localProduct = $gift->getLocalEntity();

        $this->disableStockManagement($localProduct);

        $this->log()->info("Successfully deactivate gift stock management.");

        return true;
    }

    /**
     * @inheritDoc
     */
    public function configure(LocalProduct $localProduct)
    {
        /** @var ProductEntityInterface|null $product */
        $product = null;

        try {
            $this->disableStockManagement($localProduct);

            $this->log()->notice("Stock management successfully deactivated for product #{$localProduct->getId()}.");
        } catch (Exception $exception) {
            $this->log()->critical("An error occurred during stock management deactivation.", $exception);

            throw $exception;
        }

        return $product;
    }

    protected function disableStockManagement(LocalProduct $localProduct)
    {
        /** @var LocalStockItem $stockItem */
        $stockItem = $this->localStockRegistry->getStockItemBySku($localProduct->getSku());

        $stockItem->setUseConfigMaxSaleQty(false);
        $stockItem->setMaxSaleQty(1);
        $stockItem->setUseConfigManageStock(false);
        $stockItem->setManageStock(false);

        $this->localStockRegistry->updateStockItemBySku($localProduct->getSku(), $stockItem);
    }
}