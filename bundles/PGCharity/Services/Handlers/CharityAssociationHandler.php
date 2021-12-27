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

/**
 * Class CharityAssociationHandler
 * @package PGCharity\Services\Handlers
 */
class CharityAssociationHandler
{
    const CHARITY_ASSOCIATION_CACHE_KEY = 'charity_associations';

    /** @var ApiFacade */
    private $charityAPIFacade;

    /** @var CacheHandler */
    private $cacheHandler;

    /** @var Logger */
    private $logger;

    public function __construct(
        ApiFacade $charityAPIFacade,
        CacheHandler $cacheHandler,
        Logger $logger
    ) {
        $this->charityAPIFacade = $charityAPIFacade;
        $this->cacheHandler = $cacheHandler;
        $this->logger = $logger;
    }

    /**
     * @throws Response
     * @throws Exception
     */
    public function getAvailableAssociations()
    {
        $this->logger->debug('Get available associations');

        $associations = $this->cacheHandler->loadEntry(self::CHARITY_ASSOCIATION_CACHE_KEY);

        if ($associations === null) {
            $associations = $this->charityAPIFacade->getAvailableAssociations();
            $associations = $associations->data->_embedded->association;
            $this->storeAssociations($associations);
        } else {
            $this->logger->debug('Available associations loaded from cache.');
        }

        return $associations;
    }

    /**
     * @param array $associations
     * @return void
     * @throws Exception
     */
    private function storeAssociations($associations)
    {
        $this->cacheHandler->saveEntry(self::CHARITY_ASSOCIATION_CACHE_KEY, $associations);
    }
}
