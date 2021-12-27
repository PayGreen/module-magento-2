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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGCharity\Services\Handlers;

use Exception;
use PGI\Module\APICharity\Services\Facades\ApiFacade;
use PGI\Module\PGClient\Exceptions\Response;
use PGI\Module\PGFramework\Services\Handlers\CacheHandler;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGModule\Services\Settings;

/**
 * Class CharityAssociationHandler
 * @package PGCharity\Services\Handlers
 */
class CharityPartnershipHandler
{
    const CHARITY_PARTNERSHIP_CACHE_KEY = 'charity_partnerships';

    /** @var ApiFacade */
    private $charityAPIFacade;

    /** @var CacheHandler */
    private $cacheHandler;

    /** @var Settings */
    private $settings;

    /** @var Logger */
    private $logger;

    public function __construct(
        ApiFacade $charityAPIFacade,
        CacheHandler $cacheHandler,
        Settings $settings,
        Logger $logger
    ) {
        $this->charityAPIFacade = $charityAPIFacade;
        $this->cacheHandler = $cacheHandler;
        $this->settings = $settings;
        $this->logger = $logger;
    }

    /**
     * @throws Response
     * @throws Exception
     */
    public function getPartnerships()
    {
        $this->logger->debug('Get partnerships');

        $partnerships = $this->cacheHandler->loadEntry(self::CHARITY_PARTNERSHIP_CACHE_KEY);

        if ($partnerships === null) {
            $partnerships = $this->charityAPIFacade->getPartnerships();
            $partnerships = $partnerships->data->_embedded->partnership;
            $this->storePartnerships($partnerships);
        } else {
            $this->logger->debug('Partnerships loaded from cache.');
        }

        return $this->orderPartnerships($partnerships);
    }

    /**
     * @param array $partnerships
     * @return void
     * @throws Exception
     */
    private function storePartnerships($partnerships)
    {
        $this->cacheHandler->saveEntry(self::CHARITY_PARTNERSHIP_CACHE_KEY, $partnerships);
    }

    /**
     * @param array $partnerships
     * @return array
     * @throws Exception
     */
    private function orderPartnerships($partnerships)
    {
        $partnershipsPositions = $this->settings->get('charity_partnerships_positions');

        if (!empty($partnershipsPositions)) {
            foreach ($partnerships as $partnership) {
                if (in_array($partnership->idPartnership, $partnershipsPositions)) {
                    $partnership->position = array_search($partnership->idPartnership, $partnershipsPositions);
                }
            }

            usort($partnerships, function ($partnershipA, $partnershipB) {
                if ($partnershipA->position === $partnershipB->position) {
                    return 0;
                }
                return ($partnershipA->position < $partnershipB->position) ? -1 : 1;
            });
        }

        return $partnerships;
    }
}
