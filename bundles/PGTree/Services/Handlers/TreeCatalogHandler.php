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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGTree\Services\Handlers;

use Exception;
use PGI\Module\PGFramework\Services\Handlers\CacheHandler;
use PGI\Module\PGIntl\Components\Translation as TranslationComponent;
use PGI\Module\PGIntl\Services\Translator;
use PGI\Module\PGShop\Interfaces\Entities\ProductEntityInterface;
use PGI\Module\PGShop\Services\Managers\ProductManager;
use PGI\Module\PGSystem\Components\Parameters;
use PGI\Module\PGTree\Services\Filters\ProductNameFilter;
use PGI\Module\PGTree\Services\Filters\ProductReferenceFilter;

/**
 * Class TreeCatalogHandler
 * @package PGTree\Services\Handlers
 */
class TreeCatalogHandler
{
    /** @var ProductManager $productManager */
    private $productManager;

    /** @var CacheHandler $cacheHandler */
    private $cacheHandler;

    /** @var Translator $translator */
    private $translator;

    /** @var ProductReferenceFilter */
    private $productReferenceFilter;

    /** @var ProductNameFilter */
    private $productNameFilter;

    /** @var Parameters */
    private $parameters;

    public function __construct(
        ProductManager $productManager,
        CacheHandler $cacheHandler,
        Translator $translator,
        ProductReferenceFilter $productReferenceFilter,
        ProductNameFilter $productNameFilter,
        Parameters $parameters
    ) {
        $this->productManager = $productManager;
        $this->cacheHandler = $cacheHandler;
        $this->translator = $translator;
        $this->productReferenceFilter = $productReferenceFilter;
        $this->productNameFilter = $productNameFilter;
        $this->parameters = $parameters;
    }

    /**
     * @throws Exception
     */
    public function build()
    {
        $this->cacheHandler->clearCacheEntry("carbon_footprint_catalog");

        $productCatalog = $this->prepareProductCatalog($this->productManager->getAll());

        $this->cacheHandler->saveEntry("carbon_footprint_catalog", $productCatalog);
    }

    public function hasData()
    {
        if ($this->cacheHandler->loadEntry("carbon_footprint_catalog") !== null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @throws Exception
     */
    public function resume()
    {
        $productCatalog = $this->cacheHandler->loadEntry("carbon_footprint_catalog");

        $notices = array();
        $error = null;

        if ($productCatalog === null) {
            $error = $this->translator->get(new TranslationComponent("blocks.tree_generate_product_catalog.error.no_cache"));
            return array($notices, $error);
        }

        $nbNoNamed = 0;
        $nbNoId = 0;
        $nbNoReference = 0;
        $nbNoWeight = 0;

        /** @var ProductEntityInterface $product */
        foreach ($productCatalog as $productEntry) {
            if (!$productEntry['id']) {
                $nbNoId++;
                continue;
            }

            if (!$productEntry['name']) {
                $nbNoNamed++;
            }

            if (!$productEntry['reference']) {
                $nbNoReference++;
            }

            if (!$productEntry['weight'] && (!$productEntry['isVirtual'])) {
                $nbNoWeight++;
            }
        }

        if ($nbNoId === 1) {
            $notices[] = $this->translator->get(new TranslationComponent("blocks.tree_generate_product_catalog.single_notice.id", array("nb_no_id" => $nbNoId)));
        } elseif ($nbNoId >= 1) {
            $notices[] = $this->translator->get(new TranslationComponent("blocks.tree_generate_product_catalog.several_notice.id", array("nb_no_id" => $nbNoId)));
        }
        
        if ($nbNoNamed === 1) {
            $notices[] = $this->translator->get(new TranslationComponent("blocks.tree_generate_product_catalog.single_notice.name", array("nb_no_name" => $nbNoNamed)));
        } elseif ($nbNoNamed >= 1) {
            $notices[] = $this->translator->get(new TranslationComponent("blocks.tree_generate_product_catalog.several_notice.name", array("nb_no_name" => $nbNoNamed)));
        }

        if ($nbNoReference === 1) {
            $notices[] = $this->translator->get(new TranslationComponent("blocks.tree_generate_product_catalog.single_notice.reference", array("nb_no_reference" => $nbNoReference)));
        } elseif ($nbNoReference >= 1) {
            $notices[] = $this->translator->get(new TranslationComponent("blocks.tree_generate_product_catalog.several_notice.reference", array("nb_no_reference" => $nbNoReference)));
        }

        if ($nbNoWeight === 1) {
            $notices[] = $this->translator->get(new TranslationComponent("blocks.tree_generate_product_catalog.single_notice.weight", array("nb_no_weight" => $nbNoWeight)));
        } elseif ($nbNoWeight >= 1) {
            $notices[] = $this->translator->get(new TranslationComponent("blocks.tree_generate_product_catalog.several_notice.weight", array("nb_no_weight" => $nbNoWeight)));
        }

        $nbTotal = count($productCatalog);

        if (($nbNoId === $nbTotal) || ($nbNoNamed === $nbTotal)) {
            $error = $this->translator->get(new TranslationComponent("blocks.tree_generate_product_catalog.error.no_product"));
        }

        return array($notices, $error);
    }

    /**
     * @throws Exception
     */
    public function getCleanedData()
    {
        $productCatalog = $this->cacheHandler->loadEntry("carbon_footprint_catalog");

        if ($productCatalog === null) {
            throw new Exception("Product catalog is empty.");
        }

        $cleanCatalog = array();

        /** @var ProductEntityInterface $product */
        foreach ($productCatalog as $productEntry) {
            $name = $productEntry['name'];
            $id = $productEntry['id'];

            if (!empty($name) && (!empty($id))) {
                $cleanCatalog[] = array(
                    'name' => $name,
                    'id' => $id,
                    'reference' => $productEntry['reference'],
                    'weight' => $productEntry['weight']
                );
            }
        }

        return $cleanCatalog;
    }

    /**
     * @param array $products
     * @return array
     * @throws Exception
     */
    protected function prepareProductCatalog($products)
    {
        $productCatalog = array();

        /** @var ProductEntityInterface $product */
        foreach ($products as $product) {
            $name = $this->productNameFilter->filter($product->getName());
            $reference = $this->productReferenceFilter->filter($product->getReference());

            if (!in_array($reference, $this->parameters['catalog_export.excluded_products'])) {
                $productCatalog[] = array(
                    'name' => $name,
                    'id' => $product->id(),
                    'reference' => (!empty($reference)) ? $reference : $product->id(),
                    'weight' => $product->getWeight(),
                    'isVirtual' => $product->isVirtual()
                );
            }
        }

        return $productCatalog;
    }
}
