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

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Cache\Manager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Cache\TypeListInterface;

/**
 * Class PGMagentoServicesOfficersConfigurationGlobalSettingsOfficer
 * @package PGMagento\Services\Officers
 */
class PGMagentoServicesOfficersConfigurationGlobalSettingsOfficer implements PGModuleInterfacesOfficersSettings
{
    /** @var ObjectManager */
    private $objectManager;

    public function __construct(ObjectManager $magento)
    {
        $this->objectManager = $magento;
    }

    /**
     * @inheritDoc
     */
    public function getOption($name, $defaultValue)
    {
        /** @var ScopeConfigInterface $scope */
        $scope = $this->objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');

        $value = $scope->getValue("payment/paygreen/$name");

        return ($value !== null) ? $value : $defaultValue;
    }

    /**
     * @inheritDoc
     */
    public function setOption($name, $value)
    {
        /** @var Config $config */
        $config = $this->objectManager->get('Magento\Config\Model\ResourceModel\Config');

        $config->saveConfig("payment/paygreen/$name", $value);

        /** @var TypeListInterface $cleaner */
        $cleaner = $this->objectManager->create('Magento\Framework\App\Cache\TypeListInterface');

        $cleaner->cleanType('config');
    }

    public function unsetOption($name)
    {
        /** @var Config $config */
        $config = $this->objectManager->get('Magento\Config\Model\ResourceModel\Config');

        $config->deleteConfig("payment/paygreen/$name");

        /** @var TypeListInterface $cleaner */
        $cleaner = $this->objectManager->create('Magento\Framework\App\Cache\TypeListInterface');

        $cleaner->cleanType('config');
    }

    public function clean()
    {
        /** @var Manager $cacheManager */
        $cacheManager = $this->objectManager->get('Magento\Framework\App\Cache\Manager');

        $cacheManager->clean(array('config'));
    }
}
