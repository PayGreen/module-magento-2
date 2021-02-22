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

abstract class PGDomainFoundationsAbstractShopHandler extends PGFrameworkFoundationsAbstractObject implements PGDomainInterfacesShopHandlerInterface
{
    const SESSION_SHOP_KEY = 'paygreen_selected_shop_primary';

    /** @var PGDomainInterfacesEntitiesShopInterface */
    private $shop = null;

    /** @var PGDomainServicesManagersShopManager */
    private $shopManager;

    /** @var PGFrameworkInterfacesHandlersSessionHandlerInterface */
    private $sessionHandler;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    /**
     * PGModuleServicesHandlersShopHandler constructor.
     * @param PGFrameworkServicesLogger $logger
     */
    public function __construct(PGFrameworkServicesLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param PGDomainServicesManagersShopManager $shopManager
     */
    public function setShopManager(PGDomainServicesManagersShopManager $shopManager)
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
     * @return PGFrameworkServicesLogger
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
     * @return PGDomainServicesManagersShopManager
     */
    public function getShopManager()
    {
        return $this->shopManager;
    }

    /**
     * @return bool
     */
    abstract public function isMultiShopActivated();

    /**
     * @return bool
     */
    abstract protected function isBackOffice();

    /**
     * @return PGDomainInterfacesEntitiesShopInterface
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
     * @param PGDomainInterfacesEntitiesShopInterface $shop
     */
    public function defineCurrentShop(PGDomainInterfacesEntitiesShopInterface $shop)
    {
        $this->shop = $shop;

        $this->sessionHandler->set(self::SESSION_SHOP_KEY, $shop->id());
    }

    /**
     * @return PGDomainInterfacesEntitiesShopInterface
     */
    protected function buildCurrentShop()
    {
        /** @var PGDomainInterfacesEntitiesShopInterface $shop */
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
     * @return PGDomainInterfacesEntitiesShopInterface|null
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
