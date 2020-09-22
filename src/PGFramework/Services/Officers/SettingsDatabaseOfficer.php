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
 * @version   1.1.1
 */

/**
 * Class PGFrameworkServicesOfficersSettingsDatabaseOfficer
 * @package PGFramework\Services\Officers
 */
class PGFrameworkServicesOfficersSettingsDatabaseOfficer implements PGFrameworkInterfacesOfficersSettingsOfficerInterface
{
    /** @var PGFrameworkEntitiesSetting[] */
    private $settings = null;

    /** @var PGFrameworkServicesManagersSettingManager */
    private $settingManager;

    /** @var PGDomainInterfacesShopHandlerInterface */
    private $shopHandler;

    public function __construct(PGFrameworkServicesManagersSettingManager $settingManager, PGDomainInterfacesShopHandlerInterface $shopHandler = null)
    {
        $this->settingManager = $settingManager;
        $this->shopHandler = $shopHandler;
    }

    protected function init()
    {
        if ($this->settings === null) {
            $this->settings = $this->settingManager->getAllByShop($this->getCurrentShop());
        }
    }

    protected function getCurrentShop()
    {
        return ($this->shopHandler !== null) ? $this->shopHandler->getCurrentShop() : null;
    }

    public function clear()
    {
        $this->settings = null;
    }

    public function getOption($key, $defaultValue = null)
    {
        $this->init();

        return isset($this->settings[$key]) ? $this->settings[$key]->getValue() : $defaultValue;
    }

    public function setOption($key, $value)
    {
        $this->init();

        $result = true;

        if (isset($this->settings[$key])) {
            $this->settings[$key]->setValue($value);

            $result = $this->settingManager->update($this->settings[$key]);
        } else {
            $this->settings[$key] = $this->settingManager->insert($key, $value, $this->getCurrentShop());
        }

        return $result;
    }

    public function unsetOption($key)
    {
        $this->init();

        $result = true;

        if (isset($this->settings[$key])) {
            $result = $this->settingManager->delete($this->settings[$key]);

            if ($result) {
                unset($this->settings[$key]);
            }
        }

        return $result;
    }

    public function hasOption($key)
    {
        $this->init();

        return isset($this->settings[$key]);
    }
}
