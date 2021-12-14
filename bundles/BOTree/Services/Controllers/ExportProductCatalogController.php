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

namespace PGI\Module\BOTree\Services\Controllers;

use PGI\Module\APITree\Services\Facades\ApiFacade;
use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGFramework\Services\Generators\CSVGenerator;
use PGI\Module\PGPayment\Exceptions\InvalidProductCatalog as InvalidProductCatalogException;
use PGI\Module\PGServer\Components\Resources\Data as DataResourceComponent;
use PGI\Module\PGServer\Components\Responses\HTTP as HTTPResponseComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use DateTime;
use Exception;
use PGI\Module\PGServer\Components\Resources\ScriptFile as ScriptFileResourceComponent;
use PGI\Module\PGTree\Services\Handlers\TreeCatalogHandler;
use PGI\Module\PGServer\Components\Responses\PaygreenModule as PaygreenModuleResponseComponent;

/**
 * Class ExportProductCatalogController
 * @package BOTree\Services\Controllers
 */
class ExportProductCatalogController extends AbstractBackofficeController
{
    const EXPORT_PRODUCT_CATALOG_FILENAME = 'export_product_catalog';

    private static $EXPORT_PRODUCT_CATALOG_COLUMNS_NAME = array('nom', 'ID-tech', 'code article', 'poids');

    /** @var CSVGenerator */
    private $csvGenerator;

    /** @var TreeCatalogHandler $treeCatalogHandler */
    private $treeCatalogHandler;

    /** @var ApiFacade */
    private $treeAPIFacade;

    public function __construct(
        CSVGenerator $csvGenerator,
        TreeCatalogHandler $treeCatalogHandler,
        ApiFacade $treeAPIFacade
    ) {
        $this->csvGenerator = $csvGenerator;
        $this->treeCatalogHandler = $treeCatalogHandler;
        $this->treeAPIFacade = $treeAPIFacade;
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayTreeGenerateProductCatalogButtonAction()
    {
        list($notices, $error) = $this->treeCatalogHandler->resume();
        $emptyCache = !$this->treeCatalogHandler->hasData();

        return $this->buildTemplateResponse('tree/block-tree-generate-product-catalog')
            ->addResource(new ScriptFileResourceComponent('/js/page-tree-export-csv.js'))
            ->addResource(new DataResourceComponent(array(
                'paygreen_generate_product_catalog_url' => $this->getLinkHandler()->buildBackOfficeUrl(
                    'backoffice.tree_config.generate_product_catalog'
                ))))
            ->addData('notices', $notices)
            ->addData('error', $error)
            ->addData('empty_cache', $emptyCache)
            ;
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayTreeExportProductCatalogButtonAction()
    {
        $emptyCache = !$this->treeCatalogHandler->hasData();

        return $this->buildTemplateResponse('tree/block-tree-export-product-catalog')
            ->addData('empty_cache', $emptyCache)
            ;
    }

    /**
     * @return HTTPResponseComponent
     * @throws Exception
     */
    public function downloadProductCatalogAction()
    {
        try {
            if (!$this->treeCatalogHandler->hasData()) {
                $this->treeCatalogHandler->build();
            }

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
            return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.tree_products_synchronization.display'));
        }
    }

    /**
     * @return HTTPResponseComponent
     * @throws Exception
     */
    public function exportProductCatalogAction()
    {
        try {
            if (!$this->treeCatalogHandler->hasData()) {
                $this->treeCatalogHandler->build();
            }

            $file = $this->getProductCatalogCSVFile();

            $fileMetadata = stream_get_meta_data($file);
            $filePath = $fileMetadata['uri'];

            $this->treeAPIFacade->exportProductCatalog($filePath);

            unlink($filePath);

            $this->success('actions.tree_export_product_catalog.success_catalog_send');
        } catch (InvalidProductCatalogException $exception) {
            $this->failure('actions.tree_export_product_catalog.invalid_product_catalog');
        }
        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.tree_products_synchronization.display'));
    }

    /**
     * @return PaygreenModuleResponseComponent
     * @throws Exception
     */
    public function generateProductCatalogAction()
    {
        $this->treeCatalogHandler->build();
        $response = new PaygreenModuleResponseComponent($this->getRequest());

        list($notices, $error) = $this->treeCatalogHandler->resume();

        $response
            ->addData('notices', $notices)
            ->addData('error', $error)
            ;

        if ($error === null) {
            $response->validate();
        }

        return $response;
    }

    /**
     * @throws InvalidProductCatalogException|Exception
     */
    protected function getProductCatalogCSV()
    {
        $productCatalog = $this->treeCatalogHandler->getCleanedData();

        return $this->csvGenerator->generateCSV(
            $productCatalog,
            self::$EXPORT_PRODUCT_CATALOG_COLUMNS_NAME
        );
    }

    /**
     * @throws InvalidProductCatalogException|Exception
     */
    protected function getProductCatalogCSVFile()
    {
        $productCatalog = $this->treeCatalogHandler->getCleanedData();

        return $this->csvGenerator->generateCSVFile(
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
}
