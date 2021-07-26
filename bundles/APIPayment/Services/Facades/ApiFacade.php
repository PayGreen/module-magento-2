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

namespace PGI\Module\APIPayment\Services\Facades;

use PGI\Module\APIPayment\Components\Replies\Transaction as TransactionReplyComponent;
use PGI\Module\APIPayment\Components\Response as ResponseComponent;
use PGI\Module\APIPayment\Exceptions\OAuth as OAuthException;
use PGI\Module\APIPayment\Exceptions\PaymentTypes as PaymentTypesException;
use PGI\Module\APIPayment\Services\Handlers\OAuthHandler;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGClient\Exceptions\ResponseFailed as ResponseFailedException;
use PGI\Module\PGClient\Services\Factories\RequestFactory;
use PGI\Module\PGClient\Services\Sender;
use PGI\Module\PGPayment\Services\Handlers\TestingPaymentHandler;
use PGI\Module\PGShop\Tools\Price as PriceTool;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class ApiFacade
 * @package APIPayment\Services\Facades
 */
class ApiFacade extends AbstractObject
{
    const VERSION = '1.0.0';

    /** @var Sender */
    private $requestSender;

    /** @var RequestFactory */
    private $requestFactory;

    /**
     * PaymentFacade constructor.
     * @param Sender $requestSender
     * @param RequestFactory $requestFactory
     */
    public function __construct(
        Sender $requestSender,
        RequestFactory $requestFactory
    ) {
        $this->requestSender = $requestSender;
        $this->requestFactory = $requestFactory;
    }

    /**
     * @return RequestFactory
     */
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * @return Sender
     */
    public function getRequestSender()
    {
        return $this->requestSender;
    }

    /**
     * return url of Authorization
     * @return string url of Authorization
     */
    public function getOAuthAutorizeEndpoint()
    {
        return $this->requestFactory->getAPIHost().'/api/auth/authorize';
    }

    /**
     * return url of auth token
     * @return string url of Authentication
     */
    public function getOAuthTokenEndpoint()
    {
        return $this->requestFactory->getAPIHost().'/api/auth/access_token';
    }

    /**
     * return url of Authentication
     * @return string url of Authentication
     */
    private function getOAuthDeclareEndpoint()
    {
        return $this->requestFactory->getAPIHost().'/api/auth';
    }

    /**
     * Authentication to server paygreen
     *
     * @param string $email
     * @param string|null $phone
     * @param string|null $ipAddress
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function getOAuthServerAccess($email, $name, $ipAddress = null)
    {
        $ipAddress = ($ipAddress === null) ? $_SERVER['REMOTE_ADDR'] : $ipAddress;

        $request = $this->getRequestFactory()->buildRequest('oauth_access')->setContent(array(
            "ipAddress" => $ipAddress,
            "serverAddress" => $ipAddress,
            "email" => $email,
            "name" => $name
        ));

        /** @var ResponseComponent $response */
        $response = $this->getRequestSender()->sendRequest($request);

        if (!$response->isSuccess()) {
            /** @var OAuthHandler $oauthHandler */
            $oauthHandler = $this->getService('handler.oauth');

            $code = $oauthHandler->computeExceptionCode($response->getCode());

            if ($code !== null) {
                throw new OAuthException(
                    $response->getMessage(),
                    $code
                );
            }
        }

        return $response;
    }

    /**
     * @param $pid
     * @return TransactionReplyComponent
     * @throws ResponseFailedException
     * @throws ResponseException
     * @throws Exception
     */
    public function getTransactionInfo($pid)
    {
        /** @var TestingPaymentHandler $testingPaymentHandler */
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

        /** @var TransactionReplyComponent $transaction */
        $transaction = new TransactionReplyComponent($data);

        return $transaction;
    }

    /**
     * Get Status of the shop
     *
     * @param string $type
     * @return ResponseComponent
     * @throws ResponseException
     * @throws ResponseFailedException
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
     * @return ResponseComponent
     * @throws ResponseException|null
     */
    public function refundOrder($pid, $amount)
    {
        if (empty($pid)) {
            return null;
        }

        $amount = PriceTool::toInteger($amount);

        $request = $this->getRequestFactory()->buildRequest('refund', array('pid' => $pid))->setContent(array(
            'amount' => $amount ? $amount : null
        ));

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param $data
     * @return ResponseComponent
     * @throws ResponseException
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
     * @return ResponseComponent
     * @throws ResponseException
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
     * @throws ResponseException
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
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function getRoundingInfo(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('get_rounding')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $data
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function validateRounding(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('validate_rounding')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $data
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function refundRounding(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('refund_rounding')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param int $pid
     * @return ResponseComponent
     * @throws ResponseException
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
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function createCash(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('create_cash')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $data
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function createXTime(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('create_xtime')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $data
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function createSubscription(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('create_subscription')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @param array $data
     * @return ResponseComponent
     * @throws ResponseException
     */
    public function createTokenize(array $data)
    {
        $request = $this->getRequestFactory()->buildRequest('create_tokenize')->setContent($data);

        return $this->getRequestSender()->sendRequest($request);
    }

    /**
     * @return ResponseComponent
     * @throws PaymentTypesException
     */
    public function paymentTypes()
    {
        try {
            $request = $this->getRequestFactory()->buildRequest('payment_types');

            return $this->getRequestSender()->sendRequest($request);
        } catch (Exception $exception) {
            throw new PaymentTypesException(
                "Unable to retrieve payment methods.",
                PaymentTypesException::CODE_PAYMENT_TYPE,
                $exception
            );
        }
    }
}
