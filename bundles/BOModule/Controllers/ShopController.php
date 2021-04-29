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
 * @version   2.0.1
 *
 */

/**
 * Class BOModuleControllersShopController
 * @package BOModule\Controllers
 */
class BOModuleControllersShopController extends BOModuleFoundationsAbstractBackofficeController
{
    /** @var PGShopInterfacesShopHandler */
    private $shopHandler;

    /** @var PGShopServicesManagersShop */
    private $shopManager;

    /** @var BOModuleServicesHandlersMenuHandler */
    private $menuHandler;

    public function __construct(
        PGShopInterfacesShopHandler $shopHandler,
        PGShopServicesManagersShop $shopManager,
        BOModuleServicesHandlersMenuHandler $menuHandler
    ) {
        $this->shopHandler = $shopHandler;
        $this->shopManager = $shopManager;
        $this->menuHandler = $menuHandler;
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    public function setCurrentShopAction()
    {
        $shop = $this->getSelectedShop();

        $this->shopHandler->defineCurrentShop($shop);

        $url = $this->getRedirectUrl();

        return $this->redirect($url);
    }

    /**
     * @return PGShopInterfacesEntitiesShop
     * @throws Exception
     */
    private function getSelectedShop()
    {
        $id_shop = $this->getRequest()->get('id');

        if ($id_shop === null) {
            throw new Exception("Shop primary not found.");
        }

        $shop = $this->shopManager->getByPrimary($id_shop);

        if ($shop === null) {
            throw new Exception("Shop #$id_shop not found.");
        }

        return $shop;
    }

    private function getRedirectUrl()
    {
        $selected = $this->getRequest()->get('selected');

        $action = $this->menuHandler->getDefaultAction();

        foreach ($this->menuHandler->getEntries() as $code => $entry) {
            if ($code === $selected) {
                $action = $entry['action'];
            }
        }

        return $this->getLinker()->buildBackOfficeUrl($action);
    }
}
