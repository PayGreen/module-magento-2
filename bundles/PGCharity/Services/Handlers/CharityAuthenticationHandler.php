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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGCharity\Services\Handlers;

use PGI\Module\APICharity\Services\Facades\ApiFacade;
use PGI\Module\APICharity\Services\Factories\ApiFacadeFactory;
use PGI\Module\PGClient\Components\Response as ResponseComponent;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGClient\Services\Factories\RequestFactory;
use PGI\Module\PGGreen\Interfaces\AuthenticationHandlerInterface;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGModule\Services\Settings;
use DateTime;
use Exception;
use stdClass;

/**
 * Class CharityAuthenticationHandler
 * @package PGCharity\Services\Handlers
 */
class CharityAuthenticationHandler implements AuthenticationHandlerInterface
{
    const REFRESH_TOKEN_VALIDITY = '1 month';

    /** @var ApiFacade */
    private $apiFacade;

    /** @var ApiFacadeFactory */
    private $apiFacadeFactory;

    /** @var Settings */
    private $settings;

    /** @var Logger */
    private $logger;

    public function __construct(
        ApiFacade $apiFacade,
        ApiFacadeFactory $apiFacadeFactory,
        Settings $settings,
        Logger $logger
    ) {
        $this->apiFacade = $apiFacade;
        $this->apiFacadeFactory = $apiFacadeFactory;
        $this->settings = $settings;
        $this->logger = $logger;
    }

    /**
     * @return boolean
     * @throws Exception
     */
    public function isConnected()
    {
        $access_token = $this->settings->get('charity_access_token');
        $token_validity = $this->settings->get('charity_access_token_validity');

        if ((!empty($access_token)) && (!empty($token_validity))) {
            $token_validity = new DateTime($token_validity);
            $now = new DateTime();

            if ($token_validity < $now) {
                $access_token = null;
            }
        }

        return (!empty($access_token)) ? true : $this->connectWithRefreshToken();
    }

    /**
     * @param string $client_id
     * @param string $username
     * @param string $password
     * @return bool
     * @throws ResponseException
     * @throws Exception
     */
    public function connect($client_id, $username, $password)
    {
        /** @var ResponseComponent $response */
        $response = $this->apiFacade->getOAuthAccess($username, $password, $client_id);

        if ($response->getHTTPCode() === 200) {
            $this->saveTokens($client_id, $response->data);
            $this->settings->set('charity_client_username', $username);
            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @throws ResponseException
     * @throws Exception
     */
    public function connectWithRefreshToken()
    {
        $this->logger->debug('Connection with refresh token.');

        if ($this->isRefreshTokenValid()) {
            $client_id = $this->getCharityClientIdFromSettings();

            /** @var ResponseComponent $response */
            $response = $this->apiFacade->refreshOAuthAccess(
                $this->settings->get('charity_refresh_token'),
                $client_id
            );

            if ($response->getHTTPCode() === 200) {
                $this->saveTokens($client_id, $response->data);

                $this->logger->debug('Refresh token connection successfully executed.');

                return true;
            }

            return false;
        }

        $this->logger->debug('Invalid refresh token, unable to connect.');

        return false;
    }

    /**
     * @param string $client_id
     * @param stdClass $data
     * @throws Exception
     */
    public function saveTokens($client_id, stdClass $data)
    {
        $dt_access_token = new DateTime("now + {$data->expires_in} seconds");
        $dt_refresh_token = new DateTime("now + " . self::REFRESH_TOKEN_VALIDITY);

        $this->settings->set('charity_access_token', $data->access_token);
        $this->settings->set('charity_access_token_validity', $dt_access_token->format(DateTime::ISO8601));
        $this->settings->set('charity_refresh_token', $data->refresh_token);
        $this->settings->set('charity_refresh_token_validity', $dt_refresh_token->format(DateTime::ISO8601));
        $this->settings->set('charity_client_id', $client_id);

        /** @var RequestFactory $requestFactory */
        $requestFactory = $this->apiFacadeFactory->getRequestFactory();

        $this->apiFacade->setRequestFactory($requestFactory);
    }

    /**
     * @throws Exception
     */
    public function disconnect()
    {
        $this->settings->reset('charity_access_token');
        $this->settings->reset('charity_refresh_token');
        $this->settings->reset('charity_access_token_validity');
        $this->settings->reset('charity_refresh_token_validity');
        $this->settings->reset('charity_client_id');
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function isRefreshTokenValid()
    {
        $refresh_token_validity = $this->settings->get('charity_refresh_token_validity');

        if ($refresh_token_validity !== null) {
            $refresh_token_validity = new DateTime($refresh_token_validity);
            $now = new DateTime();

            return ($refresh_token_validity > $now);
        }

        return false;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getCharityClientIdFromSettings()
    {
        $client_id = $this->settings->get('charity_client_id');
        if ($client_id !== null) {
            return $client_id;
        } else {
            throw new Exception("Undefined 'charity_client_id' setting.");
        }
    }
}
