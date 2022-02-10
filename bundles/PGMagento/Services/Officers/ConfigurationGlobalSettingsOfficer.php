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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGMagento\Services\Officers;

use Magento\Framework\App\ObjectManager as LocalObjectManager;
use Magento\Framework\App\Cache\Manager as LocalManager;
use Magento\Framework\App\Config\ScopeConfigInterface as LocalScopeConfigInterface;
use Magento\Config\Model\ResourceModel\Config as LocalConfig;
use Magento\Framework\App\Cache\TypeListInterface as LocalTypeListInterface;
use PGI\Module\PGModule\Interfaces\Officers\SettingsOfficerInterface;

/**
 * Class ConfigurationGlobalSettingsOfficer
 * @package PGMagento\Services\Officers
 */
class ConfigurationGlobalSettingsOfficer implements SettingsOfficerInterface
{
    /** @var LocalObjectManager */
    private $objectManager;

    public function __construct(LocalObjectManager $magento)
    {
        $this->objectManager = $magento;
    }

    /**
     * @inheritDoc
     */
    public function getOption($name, $defaultValue)
    {
        /** @var LocalScopeConfigInterface $scope */
        $scope = $this->objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');

        $value = $scope->getValue("payment/paygreen/$name");

        return ($value !== null) ? $value : $defaultValue;
    }

    /**
     * @inheritDoc
     */
    public function setOption($name, $value)
    {
        /** @var LocalConfig $config */
        $config = $this->objectManager->get('Magento\Config\Model\ResourceModel\Config');

        $config->saveConfig("payment/paygreen/$name", $value);

        /** @var LocalTypeListInterface $cleaner */
        $cleaner = $this->objectManager->create('Magento\Framework\App\Cache\TypeListInterface');

        $cleaner->cleanType('config');
    }

    public function unsetOption($name)
    {
        /** @var LocalConfig $config */
        $config = $this->objectManager->get('Magento\Config\Model\ResourceModel\Config');

        $config->deleteConfig("payment/paygreen/$name");

        /** @var LocalTypeListInterface $cleaner */
        $cleaner = $this->objectManager->create('Magento\Framework\App\Cache\TypeListInterface');

        $cleaner->cleanType('config');
    }

    public function clean()
    {
        /** @var LocalManager $cacheManager */
        $cacheManager = $this->objectManager->get('Magento\Framework\App\Cache\Manager');

        $cacheManager->clean(array('config'));
    }
}
