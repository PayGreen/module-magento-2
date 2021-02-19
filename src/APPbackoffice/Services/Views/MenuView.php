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
 * @version   1.2.3
 *
 */

class APPbackofficeServicesViewsMenuView extends PGViewServicesView
{
    /** @var APPbackofficeServicesHandlersMenuHandler */
    private $menuHandler;

    /** @var PGDomainServicesManagersShopManager */
    private $shopManager;

    /** @var PGDomainInterfacesShopHandlerInterface */
    private $shopHandler;

    public function __construct(
        APPbackofficeServicesHandlersMenuHandler $menuHandler,
        PGDomainServicesManagersShopManager $shopManager,
        PGDomainInterfacesShopHandlerInterface $shopHandler
    ) {
        $this->menuHandler = $menuHandler;
        $this->shopManager = $shopManager;
        $this->shopHandler = $shopHandler;

        $this->setTemplate('block-menu');
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        $data = parent::getData();

        if (!array_key_exists('selected', $data)) {
            throw new Exception("MenuView require 'selected' attribut.");
        }

        $data['entries'] = $this->menuHandler->getEntries();

        if ($this->menuHandler->isShopSelectorActivated() && $this->shopHandler->isMultiShopActivated()) {
            $data['shops'] = $this->shopManager->getAll();
            $data['currentShop'] = $this->shopHandler->getCurrentShop();
        } else {
            $data['shops'] = array();
        }

        return $data;
    }
}
