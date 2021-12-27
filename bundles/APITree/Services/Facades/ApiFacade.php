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

namespace PGI\Module\APITree\Services\Facades;

use PGI\Module\APITree\Components\Replies\CarbonFootprint as CarbonFootprintReplyComponent;
use PGI\Module\PGClient\Components\Response as ResponseComponent;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGClient\Components\Request as RequestComponent;
use PGI\Module\PGClient\Services\Factories\RequestFactory;
use PGI\Module\PGClient\Services\Sender;
use Exception;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGFramework\Services\Handlers\RequirementHandler;

/**
 * Class ApiFacade
 * @package APITree\Services\Facades
 */
class ApiFacade
{
    const VERSION = '1.0.0';

    /** @var Sender */
    private $requestSender;

    /** @var RequestFactory */
    private $requestFactory;

    /** @var Settings */
    private $settings;

    /** @var RequirementHandler */
    private $requirementHandler;

    /**
     * PaymentFacade constructor.
     * @param Sender $requestSender
     * @param RequestFactory $requestFactory
     * @param Settings $settings
     * @param RequirementHandler $requirementHandler
     */
    public function __construct(
        Sender $requestSender,
        RequestFactory $requestFactory,
        Settings $settings,
        RequirementHandler $requirementHandler
    ) {
        $this->requestSender = $requestSender;
        $this->requestFactory = $requestFactory;
        $this->settings = $settings;
        $this->requirementHandler = $requirementHandler;
    }

    /**
     * @return RequestFactory
     */
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * @param RequestFactory $requestFactory
     * @return void
     */
    public function setRequestFactory(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * @return Sender
     */
    public function getRequestSender()
    {
        return $this->requestSender;
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $client_id
     * @param string $grant_type
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function getOAuthAccess($username, $password, $client_id, $grant_type = 'password')
    {
        $request = $this->getRequestFactory()->buildRequest('oauth_access', array(), false)
            ->setContent(array(
                'username' => $username,
                'password' => $password,
                'client_id' => $client_id,
                'grant_type' => $grant_type
            ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $refresh_token
     * @param string $client_id
     * @param string $grant_type
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function refreshOAuthAccess($refresh_token, $client_id, $grant_type = 'refresh_token')
    {
        $request = $this->getRequestFactory()->buildRequest('oauth_refresh_access', array(), false)
            ->setContent(array(
                'refresh_token' => $refresh_token,
                'client_id' => $client_id,
                'grant_type' => $grant_type
            ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $account_id
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function getAccountInfos($account_id)
    {
        $request = $this->getRequestFactory()->buildRequest('get_account_infos', array(
            'account_id' => $account_id
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $account_id
     * @param string $username
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function getUserData($account_id, $username)
    {
        $request = $this->getRequestFactory()->buildRequest('get_user_data', array(
            'account_id' => $account_id,
            'username' => $username
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * CCarbon transports mode
     *
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getCcarbonTransportsMode()
    {
        $request = $this->getRequestFactory()->buildRequest('get_ccarbon_transports_mode');

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * This is the first call you have to make in order to open a Carbon Footprint to register all carbon emissions for
     * one of your customer shopping path. All you need is to provide the API with a unique idFootprint that will
     * serve as an identifier for all data you will add later. As a response, you will get a Footprint with a CREATED
     * status.
     *
     * @param string $fingerprint
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function createEmptyFootprint(
        $fingerprint
    ) {
        $request = $this->getRequestFactory()->buildRequest('create_carbon_footprints')->setContent(array(
            'idFootprint' => $fingerprint
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Add web carbon emissions to the Footprint.
     *
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @param int $countPages
     * @param string $userAgent
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function addWebCarbonEmission(
        $fingerprint,
        $countPages,
        $userAgent = ''
    ) {
        $request = $this->getRequestFactory()->buildRequest('add_web_carbon_emission')->setContent(array(
            'fingerprint' => $fingerprint,
            'countPages' => $countPages,
            'userAgent' => $userAgent
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Add transportation carbon emissions to the Footprint.
     *
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @param int $weightPackages Accumulated weight of all packages transported (in kilogram)
     * @param int $countPackages
     * @param array $adresses Must have at least 2 Addresses in order to have a departure and destination point.
     * Order is important and is linked to the order of transports.
     * @param array $transports Define the kind of Transport used between the addresses.
     * Multiple Transport type can be added, one less than the number of addresses declared.
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function addTransportationCarbonEmission(
        $fingerprint,
        $weightPackages,
        $countPackages,
        $adresses,
        $transports
    ) {
        $request = $this->getRequestFactory()->buildRequest('add_transportation_carbon_emission', array(
            'fingerprint' => $fingerprint
        ))->setContent(array(
            'weightPackages' => $weightPackages,
            'countPackages' => $countPackages,
            'addresses' => $adresses,
            'transports' => $transports
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @throws ResponseException
     */
    public function removeTransportationCarbonEmission($fingerprint)
    {
        $request = $this->getRequestFactory()->buildRequest('remove_transportation_carbon_emission', array(
            'fingerprint' => $fingerprint
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * List all your Footprints with pagination and status filtering.
     *
     * @param int $pageNumber Page number is 1 by default
     * @param int $pageLimite Page limit is 50 by default
     * @param string $status Status of the Carbon Footprints you want to display ('ONGOING', 'CLOSED', 'PURCHASED')
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getAllCarbonFootprints($pageNumber = 1, $pageLimite = 50, $status = '')
    {
        $request = $this->getRequestFactory()->buildRequest('get_all_carbon_footprints')->setContent(array(
            'page' => $pageNumber,
            'limit' => $pageLimite,
            'status' => $status
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Display the current emissions estimates of a Carbon Footprint.
     * The route is available both before and after closing of the Footprint.
     * It will simply return the results with the contributions already provided.
     *
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @param bool $detailed
     * @return CarbonFootprintReplyComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getCarbonFootprintEstimation($fingerprint, $detailed = false)
    {
        $request = $this->getRequestFactory()->buildRequest(
            'get_carbon_footprint_estimation',
            array(
                'fingerprint' => $fingerprint,
                'detailed' => ($detailed) ? 1 : 0
            )
        );

        $request = $this->checkTestMode($request);

        $response = $this->getRequestSender()->sendRequest($request);

        /** @var CarbonFootprintReplyComponent $carbonFootprint */
        $carbonFootprint = new CarbonFootprintReplyComponent($response->data);

        return $carbonFootprint;
    }

    /**
     * Close carbon footprint
     *
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function closeCarbonFootprint($fingerprint)
    {
        $request = $this->getRequestFactory()->buildRequest(
            'update_carbon_footprint_status',
            array('fingerprint' => $fingerprint)
        )->setContent(array(
            'status' => 'CLOSED'
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Create a carbon footprint purchase
     *
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function createCarbonFootprintPurchase($fingerprint)
    {
        $request = $this->getRequestFactory()->buildRequest(
            'update_carbon_footprint_status',
            array('fingerprint' => $fingerprint)
        )->setContent(array(
            'status' => 'PURCHASED'
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * List all your Purchases with pagination
     *
     * @param int $pageNumber Page number is 1 by default
     * @param int $pageLimite Page limit is 50 by default
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getAllCarbonPurchases($pageNumber = 1, $pageLimit = 50)
    {
        $request = $this->getRequestFactory()->buildRequest('get_all_carbon_purchases')->setContent(array(
            'page' => $pageNumber,
            'limit' => $pageLimit
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Get a specific carbon purchase
     *
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getCarbonPurchase($fingerprint)
    {
        $request = $this->getRequestFactory()->buildRequest(
            'get_carbon_purchase',
            array('fingerprint' => $fingerprint)
        );

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Get the statictics report for a given period
     *
     * @param string $beginDate
     * @param string $endDate
     * @param string $onlyNotRefundable
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getCarbonStatisticsReport($beginDate, $endDate, $onlyNotRefundable = 0)
    {
        $request = $this->getRequestFactory()->buildRequest('get_carbon_statistics_report')->setContent(array(
            'begin' => $beginDate,
            'end' => $endDate,
            'onlyNotRefundable' => (int) $onlyNotRefundable
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param $csvFile
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function exportProductCatalog($csvFile)
    {
        $access_token = $this->settings->get('tree_access_token');

        $request = $this->getRequestFactory()->buildRequest('export_product_catalog')->setContent(array(
            'inputCsv' => $this->curlFileCreate($csvFile, 'text/csv', 'products.csv'),
        ))->setHeaders(array(
            "Accept: */*",
            "Content-Type: multipart/form-data",
            "Accept-Encoding: gzip, deflate, br",
            "Cache-Control: no-cache",
            "Authorization: Bearer $access_token"
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request, false);
    }

    /**
     * @param string $productReference,
     * @param string $productName
     * @param float $productWeight
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function createProductReference($productReference, $productName, $productWeight)
    {
        $request = $this->getRequestFactory()->buildRequest('create_product_reference')->setContent(array(
            'productExternalReference' => $productReference,
            'productName' => $productName,
            'productWeight' => $productWeight
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @param string $productReference ,
     * @param int $quantity
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function addProductCarbonEmission(
        $fingerprint,
        $productReference,
        $quantity
    ) {
        $request = $this->getRequestFactory()->buildRequest('add_product_carbon_emission', array(
            'idFootprint' => $fingerprint
        ))->setContent(array(
            'productExternalReference' => $productReference,
            'quantity' => $quantity
        ));

        $request = $this->checkTestMode($request);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function deleteProductCarbonEmission(
        $fingerprint
    ) {
        $request = $this->getRequestFactory()->buildRequest('delete_product_carbon_emission', array(
            'idFootprint' => $fingerprint
        ));

        $request = $this->checkTestMode($request);
        
        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param RequestComponent $request
     * @return RequestComponent
     * @throws Exception
     */
    private function checkTestMode(RequestComponent $request)
    {
        if ($this->isTestModeActivated()) {
            if (!$this->isAccessAvailable()) {
                throw new Exception('You must have a signed SEPA mandate to be able to call the Climate API.');
            }

            $request->addHeaders(array(
                "X-TEST-MODE: 1"
            ));
        }

        return $request;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function isTestModeActivated()
    {
        return $this->settings->get('tree_test_mode');
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function isAccessAvailable()
    {
        return $this->requirementHandler->isFulfilled('tree_access_available_without_activation');
    }

    /**
     * @param $filename
     * @param string $mimetype
     * @param string $postname
     * @return string
     */
    private function curlFileCreate($filename, $mimetype = '', $postname = '')
    {
        if (!function_exists('curl_file_create')) {
            $curlFile = "@$filename;filename="
                . ($postname ?: basename($filename))
                . ($mimetype ? ";type=$mimetype" : '');
        } else {
            $curlFile = curl_file_create($filename, $mimetype, $postname);
        }

        return $curlFile;
    }
}
