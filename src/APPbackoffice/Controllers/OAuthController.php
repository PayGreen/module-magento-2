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
 * @version   1.2.5
 *
 */

class APPbackofficeControllersOAuthController extends APPbackofficeFoundationsAbstractBackofficeController
{
    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws OAuthException
     * @throws Exception
     */
    public function sendOAuthRequestAction()
    {
        /** @var PGClientServicesHandlersOAuthHandler $oauthHandler */
        $oauthHandler = $this->getService('handler.oauth');

        $url = $oauthHandler->buildOAuthRequestUrl();

        $this->getLogger()->info("Redirect to OAuth url : " . $url);

        return $this->redirect($url, 303);
    }

    /**
     *  Authentication and full private key and unique id
     * @throws Exception
     * @throws OAuthException
     */
    public function processOAuthResponseAction()
    {
        /** @var PGClientServicesHandlersOAuthHandler $oauthHandler */
        $oauthHandler = $this->getService('handler.oauth');

        $code = $_GET['code'];

        if (empty($code)) {
            throw new Exception("OAuth code not found.");
        }

        if ($oauthHandler->connectWithOAuthCode($code)) {
            $this->success('backoffice.actions.credentials.save.result.success');
        } else {
            $this->failure('backoffice.errors.connection');
        }

        return $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.account.display'));
    }
}
