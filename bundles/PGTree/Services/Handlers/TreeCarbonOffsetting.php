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
 * @version   2.0.0
 *
 */

/**
 * Class PGTreeServicesHandlersTreeCarbonOffsetting
 * @package PGTree\Services\Handlers
 */
class PGTreeServicesHandlersTreeCarbonOffsetting
{
    /** @var PGTreeServicesTreeFacade */
    private $treeFacade;

    /** @var PGTreeServicesHandlersTreeAuthentication */
    private $treeAuthenticationHandler;

    /** @var PGTreeCommonServicesHandlersFingerPrint */
    private $fingerprintHandler;

    /** @var PGViewServicesHandlersViewHandler */
    private $viewHandler;

    /** @var PGModuleServicesHandlersBehavior */
    private $behaviorHandler;
    
    /** @var PGModuleServicesSettings */
    private $settings;

    /** @var PGIntlServicesTranslator $translator */
    private $translator;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(
        PGTreeServicesTreeFacade $treeFacade,
        PGTreeServicesHandlersTreeAuthentication $treeAuthenticationHandler,
        PGTreeCommonServicesHandlersFingerPrint $fingerprintHandler,
        PGViewServicesHandlersViewHandler $viewHandler,
        PGModuleServicesHandlersBehavior $behaviorHandler,
        PGModuleServicesSettings $settings,
        PGIntlServicesTranslator $translator,
        PGModuleServicesLogger $logger
    ) {
        $this->treeFacade = $treeFacade;
        $this->treeAuthenticationHandler = $treeAuthenticationHandler;
        $this->fingerprintHandler = $fingerprintHandler;
        $this->viewHandler = $viewHandler;
        $this->behaviorHandler = $behaviorHandler;
        $this->settings = $settings;
        $this->translator = $translator;
        $this->logger = $logger;
    }

    public function computeCarbonOffsetting(
        PGShopInterfacesShopable $shopable,
        PGShopInterfacesEntitiesCustomer $customer,
        PGShopInterfacesEntitiesCarrier $carrier
    ) {
        $aggregatedFingerPrint = $this->fingerprintHandler->generateFingerprintDatas($shopable, $customer, $carrier);

        if ($aggregatedFingerPrint === null) {
            $this->logger->error('Missing values in fingerprint data. Carbon offsetting computing aborted.');
            return;
        }

        try {
            if ($this->treeAuthenticationHandler->isConnected()) {
                $response = $this->treeFacade->getAPIFacade()->addWebCarbonEmission(
                    (string) $aggregatedFingerPrint['fingerprint'] . mt_rand(0, PHP_INT_MAX),
                    (string) $aggregatedFingerPrint['nbImage'],
                    (string) $aggregatedFingerPrint['nbPage'],
                    (int) $aggregatedFingerPrint['useTime'],
                    $_SERVER['HTTP_USER_AGENT'] // @todo Récupération du user agent à revoir
                );

                $this->logger->debug('Add web browsing footprint.');

                if ($this->isResponseValid($response, 201)) {
                    $response = $this->treeFacade->getAPIFacade()->addTransportationCarbonEmission(
                        $response->data->fingerprint,
                        (int) $aggregatedFingerPrint['weight'],
                        (int) $aggregatedFingerPrint['nbPackage'],
                        array(
                            $this->getMerchantDepositAddress(),
                            $this->createTransportAddress(
                                $aggregatedFingerPrint['clientAddress']['address'],
                                $aggregatedFingerPrint['clientAddress']['zipcode'],
                                $aggregatedFingerPrint['clientAddress']['city'],
                                $aggregatedFingerPrint['clientAddress']['country']
                            )
                        ),
                        array($this->createTransport("standard-delivery-van"))
                    );
    
                    $this->logger->debug('Add transport footprint.');
                }
                
                if ($this->isResponseValid($response, 201)) {
                    $response = $this->treeFacade->getAPIFacade()->closeCarbonFootprint(
                        $response->data->fingerprint
                    );

                    $this->logger->debug('Closing carbon footprint.');
                }

                if ($this->isResponseValid($response, 200)) {
                    $response = $this->treeFacade->getAPIFacade()->getCarbonFootprintEstimation(
                        $response->data->fingerprint
                    );

                    $this->logger->debug('Display carbon footprint.');
                }

                return $response;
            }
        } catch (Exception $exception) {
            $this->logger->error(
                "An error occured during carbon offset computing : " . $exception->getMessage(),
                $exception
            );
        }
    }

    /**
     * @param PGShopInterfacesProvisionersPostOrder $postOrderProvisionner
     * @return string|void
     * @throws Exception
     */
    public function displayCarbonOffsetting(PGShopInterfacesProvisionersPostOrder $postOrderProvisionner)
    {
        if (!$this->behaviorHandler->get('tree_activation')) {
            $this->logger->debug('ClimateKit is deactivated. Carbon offset computing aborted.');
            return;
        }

        $result = '';

        try {
            if ($this->treeAuthenticationHandler->isConnected()) {
                $carbonOffset = $this->computeCarbonOffsetting(
                    $postOrderProvisionner->getOrder(),
                    $postOrderProvisionner->getCustomer(),
                    $postOrderProvisionner->getCarrier()
                );

                if ($carbonOffset) {
                    $result = $this->viewHandler->renderTemplate('carbon-offset-merchant', array(
                        "carbon_offset" => round($carbonOffset->getEstimatedCarbon() * 1000, 2)
                    ));
                }
            }
        } catch (Exception $exception) {
            $this->logger->error(
                "An error occured during carbon offset displaying : " . $exception->getMessage(),
                $exception
            );
        }

        return $result;
    }

    protected function createTransportAddress($address, $zipCode, $city, $country)
    {
        $data = array(
            'address' => $address,
            'zipCode' => $zipCode,
            'city' => $city,
            'country' => $country
        );

        return (object) $data;
    }

    protected function createTransport($type)
    {
        $data = array(
            'uuidTransport' => $type
        );

        return (object) $data;
    }
    
    /**
     * @param PGClientComponentsResponse $response
     * @param int $status_code
     * @return boolean
     */
    protected function isResponseValid(PGClientComponentsResponse $response, $status_code)
    {
        if ($response->getHTTPCode() !== $status_code) {
            $this->logger->error(
                "An error occured during the API request: ",
                array($response->getRequest(), $response)
            );
            throw new Exception("An error occured during the API request.");
        }

        return true;
    }

    /**
     * @return object
     * @throws Exception
     */
    protected function getMerchantDepositAddress()
    {
        $address_data = array();

        $address_data['address'] = $this->settings->get('shipping_address_line_1');
        $address_data['zipCode'] = $this->settings->get('shipping_address_zipcode');
        $address_data['city'] = $this->settings->get('shipping_address_city');

        $shipping_address_line_2 = $this->settings->get('shipping_address_line_2');

        if (!empty($shipping_address_line_2)) {
            $address_data['address'] .= ', ' . $shipping_address_line_2;
        }

        $iso = $this->settings->get('shipping_address_country');
        $address_data['country'] = $this->translator->get("countries.$iso");

        foreach ($address_data as $key => $value) {
            if (empty($value)) {
                $this->logger->error("Merchant deposit address is invalid '$key' is empty : ", $value);
                throw new Exception("Merchant deposit address is invalid");
            }
        }

        return $this->createTransportAddress(
            $address_data['address'],
            $address_data['zipCode'],
            $address_data['city'],
            $address_data['country']
        );
    }
}
