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

namespace PGI\Module\BOTree\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGFramework\Services\Generators\CSVGenerator;
use PGI\Module\PGPayment\Exceptions\InvalidProductCatalog as InvalidProductCatalogException;
use PGI\Module\PGServer\Components\Responses\HTTP as HTTPResponseComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGShop\Interfaces\Entities\ProductEntityInterface;
use PGI\Module\PGShop\Interfaces\Repositories\ProductRepositoryInterface;
use PGI\Module\PGTree\Services\Handlers\TreeAuthenticationHandler;
use DateTime;
use Exception;

/**
 * Class ExportProductCatalogController
 * @package BOTree\Services\Controllers
 */
class ExportProductCatalogController extends AbstractBackofficeController
{
    const EXPORT_PRODUCT_CATALOG_FILENAME = 'export_product_catalog';

    private static $EXPORT_PRODUCT_CATALOG_COLUMNS_NAME = array('nom', 'code article', 'poids');

    /** @var CSVGenerator */
    private $csvGenerator;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var TreeAuthenticationHandler */
    private $treeAuthenticationHandler;

    public function __construct(
        CSVGenerator $csvGenerator,
        ProductRepositoryInterface $productRepository,
        TreeAuthenticationHandler $treeAuthenticationHandler
    ) {
        $this->csvGenerator = $csvGenerator;
        $this->productRepository = $productRepository;
        $this->treeAuthenticationHandler = $treeAuthenticationHandler;
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayTreeExportProductCatalogButtonAction()
    {
        return $this->buildTemplateResponse('tree/block-tree-export-product-catalog');
    }

    /**
     * @return HTTPResponseComponent
     * @throws Exception
     */
    public function downloadProductCatalogAction()
    {
        try {
            $productsCSV = $this->getProductCatalogCSV();

            $datetime = new DateTime();
            $filename = self::EXPORT_PRODUCT_CATALOG_FILENAME . '_' . $datetime->getTimestamp() . '.csv';

            return $this->buildHTTPResponse($productsCSV, array(
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '";',
                'Content-Transfer-Encoding' => 'UTF-8'
            ));
        } catch (InvalidProductCatalogException $exception) {
            $this->failure('actions.tree_export_product_catalog.invalid_product_catalog');
            return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.tree_config.display'));
        }
    }

    /**
     * @return string
     * @throws InvalidProductCatalogException
     */
    protected function getProductCatalogCSV()
    {
        $productCatalog = $this->prepareProductCatalog($this->productRepository->findAll());

        return $this->csvGenerator->generateCSV(
            $productCatalog,
            self::$EXPORT_PRODUCT_CATALOG_COLUMNS_NAME
        );
    }

    /**
     * @param $content
     * @param $headers
     * @return HTTPResponseComponent
     * @throws Exception
     */
    protected function buildHTTPResponse($content, $headers)
    {
        $response = new HTTPResponseComponent($this->getRequest());

        foreach ($headers as $name => $value) {
            $response->setHeader($name, $value);
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * @param array $products
     * @return array
     * @throws InvalidProductCatalogException
     */
    protected function prepareProductCatalog($products)
    {
        $productCatalog = array();

        /** @var ProductEntityInterface $product */
        foreach ($products as $product) {
            $name = $product->getName();
            $reference = $product->getReference();

            if (empty($name) || (empty($reference))) {
                throw new InvalidProductCatalogException('Invalid product catalog');
            }

            $productCatalog[] = array(
                'name' => $name,
                'reference' => $reference,
                'weight' => $product->getWeight()
            );
        }

        return $productCatalog;
    }
}