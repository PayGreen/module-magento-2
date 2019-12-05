<?php
/**
 * 2014 - 2019 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.4
 */

/**
 * Class PGClientServicesApiFacade
 * @package PGClient\Services
 */
class PGClientServicesApiFacade extends PGFrameworkFoundationsAbstractObject
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
     * 3
     * return url of Authorization
     * @return string url of Authorization
     */
    public function getOAuthAutorizeEndpoint()
    {
        return $this->requestFactory->getAPIHost().'/api/auth/authorize';
    }

    /**
     * 4
     * return url of auth token
     * @return string url of Authentication
     */
    public function getOAuthTokenEndpoint()
    {
        return $this->requestFactory->getAPIHost().'/api/auth/access_token';
    }

    /**
     * return url of Authentication
     * 2
     * @return string url of Authentication
     */
    private function getOAuthDeclareEndpoint()
    {
        return $this->requestFactory->getAPIHost().'/api/auth';
    }

    /**
     * To check if private Key and Unique Id are valids
     *
     * @return bool
     */
    public function validAccess()
    {
        /** @var PGClientEntitiesResponse $response */
        $response = $this->getRequestFactory()->buildRequest('are_valid_ids');

        return (bool) $response->data;
    }

    /**
     * Authentication to server paygreen
     *
     * @param string $email
     * @param string $name
     * @param string|null $phone
     * @param string|null $ipAddress
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function getOAuthServerAccess($email, $name, $phone = null, $ipAddress = null)
    {
        $request = $this->getRequestFactory()->buildRequest('oauth_access')->setContent(array(
            "ipAddress" => $ipAddress ?: $_SERVER['REMOTE_ADDR'],
            "email" => $email,
            "name" => $name
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param $pid
     * @return PGClientEntitiesPaygreenTransaction
     * @throws PGClientExceptionsFailedResponseException
     * @throws PGClientExceptionsPaymentRequestException
     * @throws Exception
     */
    public function getTransactionInfo($pid)
    {
        /** @var PGDomainServicesHandlersTestingPaymentHandler $testingPaymentHandler */
        $testingPaymentHandler = $this->getService('handler.payment_testing');

        $fake = (PAYGREEN_ENV === 'DEV') && $testingPaymentHandler->isFakeRequest();


        if ($fake) {
            $data = $testingPaymentHandler->buildFakeResponse($pid);
        } else {
            $request = $this->getRequestFactory()->buildRequest('get_datas', array(
                'pid' => $pid
            ));

            $response = $this->getRequestSender()->sendRequest($request);

            $data = (array) $response->data;
        }

        /** @var PGClientEntitiesPaygreenTransaction $transaction */
        $transaction = new PGClientEntitiesPaygreenTransaction($data);

        return $transaction;
    }

    /**
     * Get Status of the shop
     *
     * @param string $type
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     * @throws PGClientExceptionsFailedResponseException
     */
    public function getStatus($type)
    {
        $request = $this->getRequestFactory()->buildRequest('get_data', array(
            'type' => $type
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * Refund an order
     *
     * @param int $pid
     * @param float $amount
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException|null
     */
    public function refundOrder($pid, $amount)
    {
        if (empty($pid)) {
            return null;
        }

        $amount = $amount * 100;

        $request = $this->getRequestFactory()->buildRequest('refund', array('pid' => $pid))->setContent(array(
            'amount' => $amount ? $amount : null
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param $data
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function sendFingerprintDatas($data)
    {
        $request = $this->getRequestFactory()->buildRequest('send_ccarbone')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * To validate the shop
     *
     * @param bool $activate
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function activateShop($activate)
    {
        $request = $this->getRequestFactory()->buildRequest('validate_shop')->setContent(array(
            'activate' => $activate
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * To check if private Key and Unique Id are valids
     *
     * @return array|bool|object
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function validIdShop()
    {
        $request = $this->getRequestFactory()->buildRequest('are-valid-ids');

        $response = $this->getRequestSender()->sendRequest($request);

        if ($response->data) {
            if (isset($response->data->error)) {
                return $response->data;
            }

            if ((bool) $response->data->success) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Get rounding informations for $paiementToken
     *
     * @param array $data
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function getRoundingInfo(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('get_rounding')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $data
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function validateRounding(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('validate_rounding')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $data
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function refundRounding(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('refund_rounding')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param int $pid
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function validDeliveryPayment($pid)
    {
        $request = $this->getRequestFactory()->buildRequest('delivery', array(
            'pid' => $pid
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $data
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function createCash(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('create_cash')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $data
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function createXTime(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('create_xtime')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $data
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function createSubscription(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('create_subscription')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $data
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function createTokenize(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('create_tokenize')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsRetrieveDataException
     */
    public function paymentTypes()
    {
        try {
            $request = $this->getRequestFactory()->buildRequest('payment_types');

            return $this->getRequestSender()->sendRequest($request);
        } catch (Exception $exception) {
            throw new PGClientExceptionsRetrieveDataException(
                "Unable to retrieve payment types.",
                PGClientExceptionsRetrieveDataException::CODE_PAYMENT_TYPE,
                $exception
            );
        }
    }
}
