<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.0
 *
 */

namespace PGI\Module\BOPayment\Services\Controllers;

use PGI\Module\APIPayment\Exceptions\OAuth as OAuthException;
use PGI\Module\APIPayment\Services\Handlers\OAuthHandler;
use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGFramework\Interfaces\SuperglobalInterface;
use PGI\Module\PGFramework\Services\Notifier;
use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;
use Exception;

/**
 * Class OAuthController
 * @package BOPayment\Services\Controllers
 */
class OAuthController extends AbstractBackofficeController
{
    const OAUTH_ERROR_DEFAULT_MESSAGE = 'actions.authentication.save.errors.oauth';

    /** @var OAuthHandler */
    private $oauthHandler;

    /** @var SuperglobalInterface */
    private $getAdapter;

    public function __construct(
        OAuthHandler $oauthHandler,
        SuperglobalInterface $getAdapter
    ) {
        $this->oauthHandler = $oauthHandler;
        $this->getAdapter = $getAdapter;
    }

    /**
     * @return RedirectionResponseComponent
     * @throws OAuthException
     * @throws Exception
     */
    public function sendOAuthRequestAction()
    {
        try {
            $url = $this->oauthHandler->buildOAuthRequestUrl();
        } catch (OAuthException $exception) {
            return $this->handleOAuthException($exception);
        }

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
        $code = $this->getAdapter['code'];

        if (empty($code)) {
            throw new Exception("OAuth code not found.");
        }

        if ($this->oauthHandler->connectWithOAuthCode($code)) {
            $this->success('actions.authentication.save.result.success');
        } else {
            $this->failure('actions.authentication.save.errors.oauth');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.account.display'));
    }

    /**
    * @param ResponseException $exception
    * @return RedirectionResponseComponent
    */
    private function handleOAuthException($exception)
    {
        /** @var ParametersComponent $parameters */
        $parameters = $this->getParameters();

        $oauthExceptionsMessages = $parameters['oauth_exceptions_messages'];

        $notification_message = self::OAUTH_ERROR_DEFAULT_MESSAGE;

        foreach ($oauthExceptionsMessages as $code => $message) {
            if ((int) $code === $exception->getCode()) {
                $notification_message = $message;
            }
        }

        $this->getLogger()->error($exception);

        $this->getNotifier()->add(Notifier::STATE_FAILURE, $notification_message);

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.account.display'));
    }
}
