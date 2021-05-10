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
 * @version   2.0.2
 *
 */

/**
 * Class APITreeServicesApiFacade
 * @package APITree\Services
 */
class APITreeServicesApiFacade extends PGSystemFoundationsObject
{
    const VERSION = '1.0.0';

    /** @var PGClientServicesRequestSender */
    private $requestSender;

    /** @var PGClientServicesRequestFactory */
    private $requestFactory;

    /**
     * PaymentFacade constructor.
     * @param PGClientServicesRequestSender $requestSender
     * @param PGClientServicesRequestFactory $requestFactory
     */
    public function __construct(
        PGClientServicesRequestSender $requestSender,
        PGClientServicesRequestFactory $requestFactory
    ) {
        $this->requestSender = $requestSender;
        $this->requestFactory = $requestFactory;
    }

    /**
     * @return PGClientServicesRequestFactory
     */
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * @return PGClientServicesRequestSender
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
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
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
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
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
     * CCarbon transports mode
     *
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
     */
    public function getCcarbonTransportsMode()
    {
        $request = $this->getRequestFactory()->buildRequest('get_ccarbon_transports_mode');

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * The first HTTP call will create a Carbon Footprint identified by its fingerprint and initialize it.
     * Further calls will add new emissions to the Footprint.
     * The response represents all carbon emissions in the Carbon Footprint.
     *
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @param int $countImages
     * @param int $countPages
     * @param int $navigationTime
     * @param string $userAgent
     * @param string $device (can be deduced from User Agent headers)
     * @param string $browser (can be deduced from User Agent headers)
     * @param string $externalId
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
     */
    public function addWebCarbonEmission(
        $fingerprint,
        $countImages,
        $countPages,
        $navigationTime,
        $userAgent = '',
        $device = '',
        $browser = '',
        $externalId = ''
    ) {
        $request = $this->getRequestFactory()->buildRequest('add_web_carbon_emission')->setContent(array(
            'fingerprint' => $fingerprint,
            'countImages' => $countImages,
            'countPages' => $countPages,
            'time' => $navigationTime,
            'userAgent' => $userAgent,
            'device' => $device,
            'browser' => $browser,
            'externalId' => $externalId
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * The first HTTP call will create a Carbon Footprint identified by its fingerprint and initialize it.
     * Further calls will add new emissions to the Footprint.
     * The response represents all carbon emissions in the Carbon Footprint.
     *
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @param int $weightPackages Accumulated weight of all packages transported (in kilogram)
     * @param int $countPackages
     * @param array $adresses Must have at least 2 Addresses in order to have a departure and destination point.
     * Order is important and is linked to the order of transports.
     * @param array $transports Define the kind of Transport used between the addresses.
     * Multiple Transport type can be added, one less than the number of addresses declared.
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
     */
    public function addTransportationCarbonEmission(
        $fingerprint,
        $weightPackages,
        $countPackages,
        $adresses,
        $transports
    ) {
        $request = $this->getRequestFactory()->buildRequest('add_transportation_carbon_emission')->setContent(array(
            'fingerprint' => $fingerprint,
            'weightPackages' => $weightPackages,
            'countPackages' => $countPackages,
            'addresses' => $adresses,
            'transports' => $transports
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * List all your Footprints with pagination and status filtering.
     *
     * @param int $pageNumber Page number is 1 by default
     * @param int $pageLimite Page limit is 50 by default
     * @param string $status Status of the Carbon Footprints you want to display ('ONGOING', 'CLOSED', 'PURCHASED')
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
     */
    public function getAllCarbonFootprints($pageNumber = 1, $pageLimite = 50, $status = '')
    {
        $request = $this->getRequestFactory()->buildRequest('get_all_carbon_footprints')->setContent(array(
            'page' => $pageNumber,
            'limit' => $pageLimite,
            'status' => $status
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Display the current emissions estimates of a Carbon Footprint.
     * The route is available both before and after closing of the Footprint.
     * It will simply return the results with the contributions already provided.
     *
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @return APITreeComponentsRepliesCarbonFootprint
     * @throws PGClientExceptionsResponse
     */
    public function getCarbonFootprintEstimation($fingerprint)
    {
        $request = $this->getRequestFactory()->buildRequest(
            'get_carbon_footprint_estimation',
            array('fingerprint' => $fingerprint)
        );

        $response = $this->getRequestSender()->sendRequest($request);

        /** @var APITreeComponentsRepliesCarbonFootprint $carbonFootprint */
        $carbonFootprint = new APITreeComponentsRepliesCarbonFootprint($response->data);

        return $carbonFootprint;
    }

    /**
     * Close carbon footprint
     *
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
     */
    public function closeCarbonFootprint($fingerprint)
    {
        $request = $this->getRequestFactory()->buildRequest(
            'update_carbon_footprint_status',
            array('fingerprint' => $fingerprint)
        )->setContent(array(
            'status' => 'CLOSED'
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Create a carbon footprint purchase
     *
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
     */
    public function createCarbonFootprintPurchase($fingerprint)
    {
        $request = $this->getRequestFactory()->buildRequest(
            'update_carbon_footprint_status',
            array('fingerprint' => $fingerprint)
        )->setContent(array(
            'status' => 'PURCHASED'
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * List all your Purchases with pagination
     *
     * @param int $pageNumber Page number is 1 by default
     * @param int $pageLimite Page limit is 50 by default
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
     */
    public function getAllCarbonPurchases($pageNumber = 1, $pageLimit = 50)
    {
        $request = $this->getRequestFactory()->buildRequest('get_all_carbon_purchases')->setContent(array(
            'page' => $pageNumber,
            'limit' => $pageLimit
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Display the purchase of CO²eq emissions offset
     *
     * @param string $fingerprint Unique string that you provide to identify a Carbon Footprint
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
     */
    public function getCarbonPurchase($fingerprint)
    {
        $request = $this->getRequestFactory()->buildRequest(
            'get_carbon_purchase',
            array('fingerprint' => $fingerprint)
        );

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Display the purchase of CO²eq emissions offset
     *
     * @param string $beginDate
     * @param string $endDate
     * @param string $onlyNotRefundable
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
     */
    public function getCarbonStatisticsReport($beginDate, $endDate, $onlyNotRefundable = 0)
    {
        $request = $this->getRequestFactory()->buildRequest('get_carbon_statistics_report')->setContent(array(
            'begin' => $beginDate,
            'end' => $endDate,
            'onlyNotRefundable' => (int) $onlyNotRefundable
        ));

        return $this->getRequestSender()->sendRequest($request);
    }
}
