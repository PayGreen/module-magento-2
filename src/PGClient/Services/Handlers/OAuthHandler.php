<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.1.1
 */

/**
 * Class PGClientServicesHandlersOAuthHandler
 */
class PGClientServicesHandlersOAuthHandler extends PGFrameworkFoundationsAbstractObject
{
    /** @var PGClientServicesApiFacade */
    private $apiFacade;

    /** @var PGFrameworkServicesSettings */
    private $settings;

    /** @var PGFrameworkServicesPathfinder */
    private $pathfinder;

    /** @var PGFrameworkInterfacesModuleFacadeInterface */
    private $moduleFacade;

    /** @var PGServerServicesLinker */
    private $linker;

    /**
     * PGClientServicesHandlersOAuthHandler constructor.
     * @param PGDomainServicesPaygreenFacade $paygreenFacade
     * @param PGFrameworkServicesSettings $settings
     * @param PGFrameworkServicesPathfinder $pathfinder
     * @param PGFrameworkInterfacesModuleFacadeInterface $moduleFacade
     * @param PGServerServicesLinker $linker
     * @throws Exception
     */
    public function __construct(
        PGDomainServicesPaygreenFacade $paygreenFacade,
        PGFrameworkServicesSettings $settings,
        PGFrameworkServicesPathfinder $pathfinder,
        PGFrameworkInterfacesModuleFacadeInterface $moduleFacade,
        PGServerServicesLinker $linker
    ) {
        $this->apiFacade = $paygreenFacade->getApiFacade();
        $this->settings = $settings;
        $this->pathfinder = $pathfinder;
        $this->moduleFacade = $moduleFacade;
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
     * @throws PGClientExceptionsPaymentRequestException
     * @throws Exception
     */
    private function createOAuthAccessToken()
    {
        $ip = $this->settings->get('oauth_ip_source');

        if (empty($ip)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        /** @var PGClientEntitiesResponse $oAuthAccessResponse */
        $oAuthAccessResponse = $this->apiFacade->getOAuthServerAccess(
            $this->moduleFacade->getShopMail(),
            $this->moduleFacade->getShopName(),
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
