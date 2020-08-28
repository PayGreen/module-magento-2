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
 * @version   1.0.1
 */

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;

class PGModuleServicesUpgradesRestoreSettingsUpgrade implements PGFrameworkInterfacesUpgradeInterface
{
    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var ConfigInterface */
    private $resourceConfig;

    /** @var PGFrameworkServicesSettings */
    private $settings;

    public function __construct(
        ObjectManager $objectManager,
        PGFrameworkServicesSettings $settings
    ) {
        $this->scopeConfig = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->resourceConfig = $objectManager->get('Magento\Framework\App\Config\ConfigResource\ConfigInterface');
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(PGFrameworkComponentsUpgradeStage $upgradeStage)
    {
        $keys = $upgradeStage->getConfig('keys');

        foreach ($keys as $oldKey => $newKey) {
            if ($this->settings->isDefined($newKey)) {
                    $value = $this->scopeConfig->getValue("payment/paygreen/$oldKey");

                    if ($value) {
                        $this->settings->set($newKey, $value);
                        $this->resourceConfig->deleteConfig("payment/paygreen/$oldKey");
                    }
            }
        }

        return true;
    }
}
