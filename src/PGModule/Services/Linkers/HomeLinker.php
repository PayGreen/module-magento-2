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

use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;

class PGModuleServicesLinkersHomeLinker implements PGServerInterfacesLinkerInterface
{
    /** @var StoreManagerInterface */
    private $storeManager;

    /**
     * PGModuleProvisionersPrePaymentProvisioner constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
    }

    public function buildUrl(array $data = array())
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }
}
