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
 * @version   2.1.1
 *
 */

/**
 * Class PGTreeServicesHandlersTreeFootprintId
 * @package PGTree\Services\Handlers
 */
class PGTreeServicesHandlersTreeFootprintId
{
    const FOOTPRINT_ID_COOKIE_NAME = 'pgFootprintId';

    private static $FOOTPRINT_VALID_STATUS = array('CREATED', 'ONGOING');

    /** @var PGFrameworkServicesHandlersCookieHandler */
    private $cookieHandler;

    /** @var PGShopServicesHandlersShopHandler */
    private $shopHandler;

    /** @var PGModuleServicesSettings */
    private $settings;

    /** @var PGModuleServicesLogger */
    private $logger;

    /** @var APITreeServicesApiFacade */
    private $treeAPIFacade;

    public function __construct(
        PGFrameworkServicesHandlersCookieHandler $cookieHandler,
        PGShopServicesHandlersShopHandler $shopHandler,
        PGModuleServicesSettings $settings,
        PGModuleServicesLogger $logger,
        APITreeServicesApiFacade $treeAPIFacade
    ) {
        $this->cookieHandler = $cookieHandler;
        $this->shopHandler = $shopHandler;
        $this->settings = $settings;
        $this->logger = $logger;
        $this->treeAPIFacade = $treeAPIFacade;
    }

    /**
     * @throws Exception
     */
    public function resetFootprintId()
    {
        $id = $this->generateUniqueFootprintId();

        $this->logger->debug("Reset '" . self::FOOTPRINT_ID_COOKIE_NAME . "' cookie.");
        $this->cookieHandler->set(self::FOOTPRINT_ID_COOKIE_NAME, $id);

        try {
            $this->treeAPIFacade->createEmptyFootprint($id);
        } catch (Exception $exception) {
            $this->logger->error("Unable to create empty footprint : " . $exception->getMessage(), $exception);
        }
    }

    /**
     * @return mixed|null
     * @throws Exception
     */
    public function getFootprintId()
    {
        if (!$this->cookieHandler->has(self::FOOTPRINT_ID_COOKIE_NAME)) {
            $this->logger->warning("Footprint cookie not found.");
            $this->resetFootprintId();
        } else {
            $id = $this->cookieHandler->get(self::FOOTPRINT_ID_COOKIE_NAME);

            try {
                /** @var APITreeComponentsRepliesCarbonFootprint $carbonFootprint */
                $carbonFootprint = $this->treeAPIFacade->getCarbonFootprintEstimation($id);

                if(!in_array($carbonFootprint->getStatus(), self::$FOOTPRINT_VALID_STATUS)) {
                    $this->logger->error("Footprint '{$id}' is not in valid status. Unrecognized status : '{$carbonFootprint->getStatus()}'.");
                    $this->resetFootprintId();
                }
            } catch (Exception $exception) {
                $this->logger->error("Footprint '{$id}' not found.");
                $this->resetFootprintId();
            }
        }

        return $this->cookieHandler->get(self::FOOTPRINT_ID_COOKIE_NAME);
    }

    /**
     * @return string
     * @throws Exception
     */
    private function generateUniqueFootprintId()
    {
        $shopId = $this->shopHandler->getCurrentShopPrimary();
        $clientId = $this->settings->get('tree_client_id');
        $footprintIdToHash = $this->getTimestampAsAString() . $shopId . $clientId . mt_rand(0, PHP_INT_MAX);

        return hash('sha256', $footprintIdToHash);
    }
    /**
     * @throws Exception
     * @return string
     */
    private function getTimestampAsAString()
    {
        $datetime = new DateTime();
        return (string) $datetime->getTimestamp();
    }
}