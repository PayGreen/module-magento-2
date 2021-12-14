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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGTree\Services\Handlers;

use DateTime;
use PGI\Module\APITree\Services\Facades\ApiFacade;
use PGI\Module\PGClient\Components\Response as ResponseComponent;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGFramework\Services\Handlers\CacheHandler;
use PGI\Module\PGModule\Services\Logger;
use Exception;
use PGI\Module\PGModule\Services\Settings;

/**
 * Class TreeAccountHandler
 * @package PGTree\Services\Handlers
 */
class TreeAccountHandler
{
    /** @var TreeAuthenticationHandler */
    private $treeAuthenticationHandler;

    /** @var ApiFacade */
    private $treeAPIFacade;

    /** @var CacheHandler */
    private $cacheHandler;

    /** @var Settings */
    private $settings;

    /** @var Logger */
    private $logger;

    public function __construct(
        TreeAuthenticationHandler $treeAuthenticationHandler,
        ApiFacade $treeAPIFacade,
        CacheHandler $cacheHandler,
        Settings $settings,
        Logger $logger
    ) {
        $this->treeAuthenticationHandler = $treeAuthenticationHandler;
        $this->treeAPIFacade = $treeAPIFacade;
        $this->cacheHandler = $cacheHandler;
        $this->settings = $settings;
        $this->logger = $logger;
    }

    /**
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getUserData()
    {
        $this->logger->debug('Get climate user data.');

        $userData = $this->cacheHandler->loadEntry('tree_user_data');

        if ($userData === null) {
            if (!$this->treeAuthenticationHandler->isConnected()) {
                throw new Exception('You must be connected to get the climate user data.');
            }

            $accountId = $this->settings->get('tree_client_id');

            if (empty($accountId)) {
                throw new Exception("'tree_client_id' settings not found.");
            }

            $username = $this->settings->get('tree_client_username');

            if (empty($username)) {
                throw new Exception("'tree_client_username' settings not found.");
            }

            $userData = $this->treeAPIFacade->getUserData($accountId, $username)->toArray();
            $this->cacheHandler->saveEntry('tree_user_data', $userData["data"]);
            $userData = $userData["data"];
        }

        return $userData;
    }

    /**
     * @return ResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function getAccountData()
    {
        $this->logger->debug('Get climate account data.');

        $accountData = $this->cacheHandler->loadEntry('tree_account_data');

        if ($accountData === null) {
            if (!$this->treeAuthenticationHandler->isConnected()) {
                throw new Exception('You must be connected to get the climate account data.');
            }

            $accountId = $this->settings->get('tree_client_id');

            if (empty($accountId)) {
                throw new Exception("'tree_client_id' settings not found.");
            }

            $accountData = $this->treeAPIFacade->getAccountInfos($accountId)->toArray();
            $this->cacheHandler->saveEntry('tree_account_data', $accountData["data"]);
            $accountData = $accountData["data"];
        }

        return $accountData;
    }

    /**
     * @return bool
     * @throws ResponseException
     */
    public function isMandateSigned()
    {
        $userData = $this->getUserData();

        if (!property_exists($userData, 'validatedFor')) {
            return false;
        }

        return ($userData->validatedFor === 'CLIMATE' || $userData->validatedFor === 'ALL');
    }


    /**
     * @return bool
     * @throws ResponseException
     */
    public function isTestModeExpired()
    {
        $accountData = $this->getAccountData();

        if (!property_exists($accountData, 'climateExpirationDate')) {
            return false;
        }

        $currentDateTime = new DateTime();
        $currentTimestamp = $currentDateTime->getTimestamp();

        return (($accountData->climateExpirationDate <= $currentTimestamp) && !$this->isMandateSigned());
    }
}
