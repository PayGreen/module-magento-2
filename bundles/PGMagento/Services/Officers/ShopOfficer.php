<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGMagento\Services\Officers;

use Magento\Store\Model\StoreManagerInterface as LocalStoreManagerInterface;
use Magento\Framework\App\State as LocalState;
use Magento\Framework\App\Area as LocalArea;
use Magento\Framework\Exception\LocalizedException as LocalLocalizedException;
use PGI\Module\PGShop\Interfaces\Officers\ShopOfficerInterface;
use PGI\Module\PGSystem\Foundations\AbstractObject;

/**
 * Class ShopOfficer
 * @package PGMagento\Services\Officers
 */
class ShopOfficer extends AbstractObject implements ShopOfficerInterface
{
    /**
     * @inheritDoc
     */
    public function isMultiShopActivated()
    {
        /** @var LocalStoreManagerInterface $storeManager */
        $storeManager = $this->getService('magento')->get('Magento\Store\Model\StoreManagerInterface');

        return !$storeManager->isSingleStoreMode();
    }

    /**
     * @inheritDoc
     */
    public function isBackOffice()
    {
        /** @var LocalState $state */
        $state = $this->getService('magento')->get('Magento\Framework\App\State');

        try {
            $area = $state->getAreaCode();
        } catch (LocalLocalizedException $exception) {
            $area = null;
        }

        return ($area === LocalArea::AREA_ADMINHTML);
    }

    /**
     * @inheritDoc
     */
    public function isShopContext()
    {
        return true;
    }
}