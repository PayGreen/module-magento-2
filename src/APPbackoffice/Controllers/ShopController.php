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
 * @version   1.2.4
 *
 */

class APPbackofficeControllersShopController extends APPbackofficeFoundationsAbstractBackofficeController
{
    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    public function setCurrentShopAction()
    {
        /** @var PGDomainInterfacesShopHandlerInterface $shopHandler */
        $shopHandler = $this->getService('handler.shop');

        $shop = $this->getSelectedShop();

        $shopHandler->defineCurrentShop($shop);

        $url = $this->getRedirectUrl();

        return $this->redirect($url);
    }

    /**
     * @return PGDomainInterfacesEntitiesShopInterface
     * @throws Exception
     */
    private function getSelectedShop()
    {
        /** @var PGDomainServicesManagersShopManager $shopManager */
        $shopManager = $this->getService('manager.shop');

        $id_shop = $this->getRequest()->get('id');

        if ($id_shop === null) {
            throw new Exception("Shop primary not found.");
        }

        $shop = $shopManager->getByPrimary($id_shop);

        if ($shop === null) {
            throw new Exception("Shop #$id_shop not found.");
        }

        return $shop;
    }

    private function getRedirectUrl()
    {
        $selected = $this->getRequest()->get('selected');

        /** @var APPbackofficeServicesHandlersMenuHandler $menuHandler */
        $menuHandler = $this->getService('handler.menu');

        $action = $menuHandler->getDefaultAction();

        foreach($menuHandler->getEntries() as $code => $entry) {
            if ($code === $selected) {
                $action = $entry['action'];
            }
        }

        return $this->getLinker()->buildBackOfficeUrl($action);
    }
}
