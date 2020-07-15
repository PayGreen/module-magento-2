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

use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class PGModuleServicesModuleFacade
 * @package PGModule\Services
 */
class PGModuleServicesModuleFacade extends PGFrameworkFoundationsAbstractObject implements PGFrameworkInterfacesModuleFacadeInterface
{
    public function isActive()
    {
        return (bool) $this->getService('settings')->get('active');
    }

    public function getApplicationName()
    {
        return 'Magento-2';
    }

    public function getApplicationVersion()
    {
        /** @var ProductMetadataInterface $productMetadata */
        $productMetadata = $this->getService('magento')->get('Magento\Framework\App\ProductMetadataInterface');

        return $productMetadata->getVersion();
    }

    public function getShopMail()
    {
        /** @var ScopeConfigInterface $scopeConfig */
        $scopeConfig = $this->getService('magento')->get('\Magento\Framework\App\Config\ScopeConfigInterface');

        return $scopeConfig->getValue(
            'trans_email/ident_sales/email',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getShopName()
    {
        /** @var StoreManagerInterface $storeManager */
        $storeManager = $this->getService('magento')->get('\Magento\Store\Model\StoreManagerInterface');

        return $storeManager->getStore()->getName();
    }

    public function getAPICredentials()
    {
        return array(
            $this->getService('settings')->get('public_key'),
            $this->getService('settings')->get('private_key')
        );
    }
}
