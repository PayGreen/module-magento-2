<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.0
 */

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class PGModuleServicesHandlersShopHandler
 *
 * @package PGModule\Services\Handlers
 */
class PGModuleServicesHandlersShopHandler extends PGDomainFoundationsAbstractShopHandler
{   /**
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
    public function isShopContext()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function isBackOffice()
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
}