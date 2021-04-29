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

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class PGMagentoServicesOfficersShopOfficer
 * @package PGMagento\Services\Officers
 */
class PGMagentoServicesOfficersShopOfficer extends PGSystemFoundationsObject implements PGShopInterfacesOfficersShop
{
    /**
     * @inheritDoc
     */
    public function isMultiShopActivated()
    {
        /** @var StoreManagerInterface $storeManager */
        $storeManager = $this->getService('magento')->get('Magento\Store\Model\StoreManagerInterface');

        return !$storeManager->isSingleStoreMode();
    }

    /**
     * @inheritDoc
     */
    public function isBackOffice()
    {
        /** @var State $state */
        $state = $this->getService('magento')->get('Magento\Framework\App\State');

        try {
            $area = $state->getAreaCode();
        } catch (LocalizedException $exception) {
            $area = null;
        }

        return ($area === Area::AREA_ADMINHTML);
    }

    /**
     * @inheritDoc
     */
    public function isShopContext()
    {
        return true;
    }
}