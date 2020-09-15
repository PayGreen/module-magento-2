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
 * @version   1.1.0
 */

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Cache\TypeListInterface;

/**
 * Class PGModuleServicesOfficersConfigurationGlobalSettingsOfficer
 * @package PGModule\Services\Officers
 */
class PGModuleServicesOfficersConfigurationGlobalSettingsOfficer implements PGFrameworkInterfacesOfficersSettingsOfficerInterface
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
}
