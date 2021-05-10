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
 * @version   2.0.2
 *
 */

/**
 * Class PGShopServicesHandlersShopHandler
 * @package PGShop\Services\Handlers
 */
class PGShopServicesHandlersShopHandler extends PGSystemFoundationsObject implements PGShopInterfacesShopHandler
{
    const SESSION_SHOP_KEY = 'paygreen_selected_shop_primary';

    /** @var PGShopInterfacesEntitiesShop */
    private $shop = null;

    /** @var PGShopServicesManagersShop */
    private $shopManager;

    /** @var PGFrameworkInterfacesHandlersSessionHandlerInterface */
    private $sessionHandler;

    /** @var PGShopInterfacesOfficersShop */
    private $shopOfficer;

    /** @var PGModuleServicesLogger */
    private $logger;

    /**
     * PGShopInterfacesShopHandler constructor.
     * @param PGModuleServicesLogger $logger
     */
    public function __construct(PGModuleServicesLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param PGShopServicesManagersShop $shopManager
     */
    public function setShopManager(PGShopServicesManagersShop $shopManager)
    {
        $this->shopManager = $shopManager;
    }

    /**
     * @param PGFrameworkInterfacesHandlersSessionHandlerInterface $sessionHandler
     */
    public function setSessionHandler(PGFrameworkInterfacesHandlersSessionHandlerInterface $sessionHandler)
    {
        $this->sessionHandler = $sessionHandler;
    }

    /**
     * @param PGShopInterfacesOfficersShop $shopOfficer
     */
    public function setShopOfficer(PGShopInterfacesOfficersShop $shopOfficer)
    {
        $this->shopOfficer = $shopOfficer;
    }

    /**
     * @return PGShopInterfacesOfficersShop
     */
    protected function getShopOfficer()
    {
        return $this->shopOfficer;
    }

    /**
     * @return PGModuleServicesLogger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return PGFrameworkInterfacesHandlersSessionHandlerInterface
     */
    public function getSessionHandler()
    {
        return $this->sessionHandler;
    }

    /**
     * @return PGShopServicesManagersShop
     */
    public function getShopManager()
    {
        return $this->shopManager;
    }

    /**
     * @return bool
     */
    public function isMultiShopActivated()
    {
        return $this->getShopOfficer()->isMultiShopActivated();
    }

    /**
     * @return bool
     */
    public function isBackOffice()
    {
        return $this->getShopOfficer()->isBackOffice();
    }

    /**
     * @return bool
     */
    public function isShopContext()
    {
        return $this->getShopOfficer()->isShopContext();
    }

    /**
     * @return PGShopInterfacesEntitiesShop
     */
    public function getCurrentShop()
    {
        if ($this->shop === null) {
            $this->shop = $this->buildCurrentShop();
        }

        return $this->shop;
    }

    /**
     * @return int
     */
    public function getCurrentShopPrimary()
    {
        return $this->getCurrentShop()->id();
    }

    /**
     * @param PGShopInterfacesEntitiesShop $shop
     */
    public function defineCurrentShop(PGShopInterfacesEntitiesShop $shop)
    {
        $this->shop = $shop;

        $this->sessionHandler->set(self::SESSION_SHOP_KEY, $shop->id());
    }

    /**
     * @return PGShopInterfacesEntitiesShop
     */
    protected function buildCurrentShop()
    {
        /** @var PGShopInterfacesEntitiesShop $shop */
        $shop = null;

        if ($this->isMultiShopActivated() && $this->isBackOffice()) {
            $shop = $this->getShopFromSession();
        }

        if ($shop === null) {
            $shop = $this->shopManager->getCurrent();
        }

        return $shop;
    }

    /**
     * @return PGShopInterfacesEntitiesShop|null
     */
    protected function getShopFromSession()
    {
        $shop = null;

        $id_shop = $this->sessionHandler->get(self::SESSION_SHOP_KEY);

        if ($id_shop !== null) {
            $shop = $this->shopManager->getByPrimary($id_shop);

            if ($shop === null) {
                $this->sessionHandler->rem(self::SESSION_SHOP_KEY);
            }
        }

        return $shop;
    }
}
