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
 * @version   1.2.0
 */

/**
 * Class PGFrameworkServicesOfficersSettingsStorageOfficer
 * @package PGFramework\Services\Officers
 */
class PGFrameworkServicesOfficersSettingsStorageOfficer implements PGFrameworkInterfacesOfficersSettingsOfficerInterface
{
    /** @var PGFrameworkInterfacesStorageInterface */
    private $settings = null;

    /** @var PGDomainInterfacesShopHandlerInterface */
    private $shopHandler;

    public function __construct(PGDomainInterfacesShopHandlerInterface $shopHandler = null)
    {
        $this->shopHandler = $shopHandler;
    }

    protected function init()
    {
        if ($this->settings === null) {
            $this->buildStorage();
        }
    }

    protected function buildStorage()
    {
        if ($this->shopHandler === null) {
            $filename = PAYGREEN_CONFIG_DIR . DIRECTORY_SEPARATOR . 'settings-global.json';
        } else {
            $id_shop = $this->shopHandler->getCurrentShopPrimary();
            $filename = PAYGREEN_CONFIG_DIR . DIRECTORY_SEPARATOR . "settings-shop-{$id_shop}.json";
        }

        $this->settings = new PGFrameworkComponentsStoragesJSONFileStorage($filename);
    }

    public function clear()
    {
        $this->buildStorage();
    }

    public function getOption($key, $defaultValue = null)
    {
        $this->init();

        return isset($this->settings[$key]) ? $this->settings[$key] : $defaultValue;
    }

    public function setOption($key, $value)
    {
        $this->init();

        $this->settings[$key] = $value;

        return true;
    }

    public function unsetOption($key)
    {
        $this->init();

        unset($this->settings[$key]);

        return true;
    }

    public function hasOption($key)
    {
        $this->init();

        return isset($this->settings[$key]);
    }
}
