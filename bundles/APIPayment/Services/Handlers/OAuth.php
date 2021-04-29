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
 * @version   2.0.1
 *
 */

/**
 * Class APIPaymentServicesHandlersOAuth
 * @package APIPayment\Services\Handlers
 */
class APIPaymentServicesHandlersOAuth extends PGSystemFoundationsObject
{
    /** @var APIPaymentServicesApiFacade */
    private $apiFacade;

    /** @var PGModuleServicesSettings */
    private $settings;

    /** @var PGSystemServicesPathfinder */
    private $pathfinder;

    /** @var PGShopServicesHandlersShopHandler */
    private $shopHandler;

    /** @var PGServerServicesLinker */
    private $linker;

    private static $OAUTH_KNOWN_EXCEPTION_CODES = array(
        "1" => APIPaymentExceptionsOAuth::ADDRESS_MISMATCH,
        "912" => APIPaymentExceptionsOAuth::INVALID_DATA
    );

    /**
     * APIPaymentServicesHandlersOAuth constructor.
     * @param PGPaymentServicesPaygreenFacade $paygreenFacade
     * @param PGModuleServicesSettings $settings
     * @param PGSystemServicesPathfinder $pathfinder
     * @param PGShopServicesHandlersShopHandler $shopHandler
     * @param PGServerServicesLinker $linker
     * @throws Exception
     */
    public function __construct(
        PGPaymentServicesPaygreenFacade $paygreenFacade,
        PGModuleServicesSettings $settings,
        PGSystemServicesPathfinder $pathfinder,
        PGShopServicesHandlersShopHandler $shopHandler,
        PGServerServicesLinker $linker
    ) {
        $this->apiFacade = $paygreenFacade->getApiFacade();
        $this->settings = $settings;
        $this->pathfinder = $pathfinder;
        $this->shopHandler = $shopHandler;
        $this->linker = $linker;

        $this->loadVendor();
    }

    /**
     * @throws Exception
     */
    protected function loadVendor()
    {
        $oAuthClasses = array(
            'OAuthClient' => '/_vendors/OAuth2/OAuthClient.php',
            'OAuthException' => '/_vendors/OAuth2/OAuthException.php',
            'OAuthInvalidArgumentException' => '/_vendors/OAuth2/OAuthInvalidArgumentException.php',
            'GrantType/IGrantType' => '/_vendors/OAuth2/GrantType/IGrantType.php',
            'GrantType/AuthorizationCode' => '/_vendors/OAuth2/GrantType/AuthorizationCode.php'
        );

        foreach ($oAuthClasses as $oAuthClass => $oAuthFile) {
            if (!class_exists($oAuthClass)) {
                require_once $this->pathfinder->toAbsolutePath('PGClient', $oAuthFile);
            }
        }
    }

    /**
     * @param int $code
     * @return int|null
     */
    public function computeExceptionCode($code)
    {
        if (array_key_exists($code, self::$OAUTH_KNOWN_EXCEPTION_CODES)) {
            return (int) self::$OAUTH_KNOWN_EXCEPTION_CODES[$code];
        }

        return null;
    }

    /**
     *  Authentication and full private key and unique id
     * @throws Exception
     * @throws OAuthException
     */
    public function buildOAuthRequestUrl()
    {
        $oAuthAccessToken = $this->createOAuthAccessToken();

        $client = $this->getOAuthClient($oAuthAccessToken);

        return $client->getAuthenticationUrl(
            $this->apiFacade->getOAuthAutorizeEndpoint(),
            $this->linker->buildBackofficeUrl('backoffice.account.oauth.response')
        );
    }

    /**
     *  Authentication and full private key and unique id
     * @throws Exception
     * @throws OAuthException
     */
    public function connectWithOAuthCode($code)
    {
        $client = $this->getOAuthClient();

        $params = array(
            'code' => $code,
            'redirect_uri' => $this->linker->buildBackofficeUrl('backoffice.account.oauth.response')
        );

        $response = $client->getAccessToken(
            $this->apiFacade->getOAuthTokenEndpoint(),
            'authorization_code',
            $params
        );

        $result = false;

        if ($response['result']['success'] == 1) {
            $data = $response['result']['data'];

            $this->settings->set('public_key', $data['id']);
            $this->settings->set('private_key', $data['privateKey']);

            $this->getService('logger')->info('OAuth connection successfully executed.');

            $result = true;
        } else {
            $this->getService('logger')->error('OAuth connection failure.');
        }

        $this->settings->reset('oauth_access');

        return $result;
    }

    /**
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    private function createOAuthAccessToken()
    {
        $ip = $this->settings->get('oauth_ip_source');

        if (empty($ip)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        /** @var PGShopInterfacesEntitiesShop $shop */
        $shop = $this->shopHandler->getCurrentShop();

        if ($shop === null) {
            throw new Exception('No shop returned.');
        }

        /** @var APIPaymentComponentsResponse $oAuthAccessResponse */
        $oAuthAccessResponse = $this->apiFacade->getOAuthServerAccess(
            $shop->getMail(),
            $shop->getName(),
            $ip
        );

        $oAuthAccessToken = (array) $oAuthAccessResponse->data;

        $this->getService('logger')->info('OAuth access token successfully created.', $oAuthAccessToken);

        $this->settings->set('oauth_access', $oAuthAccessToken);

        return $oAuthAccessToken;
    }

    /**
     * @param array $oAuthAccessToken
     * @return OAuthClient
     * @throws OAuthException
     * @throws Exception
     */
    private function getOAuthClient(array $oAuthAccessToken = array())
    {
        if (empty($oAuthAccessToken)) {
            $oAuthAccessToken = $this->settings->get('oauth_access');

            if (empty($oAuthAccessToken)) {
                throw new OAuthException("OAuth access token not found.");
            }
        }

        return new OAuthClient(
            $oAuthAccessToken['accessPublic'],
            $oAuthAccessToken['accessSecret'],
            OAuthClient::AUTH_TYPE_AUTHORIZATION_BASIC
        );
    }
}
