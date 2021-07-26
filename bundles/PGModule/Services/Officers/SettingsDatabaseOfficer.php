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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGModule\Services\Officers;

use PGI\Module\PGModule\Entities\Setting;
use PGI\Module\PGModule\Interfaces\Officers\SettingsOfficerInterface;
use PGI\Module\PGModule\Services\Managers\SettingManager;
use PGI\Module\PGShop\Interfaces\Handlers\ShopHandlerInterface;

/**
 * Class SettingsDatabaseOfficer
 * @package PGModule\Services\Officers
 */
class SettingsDatabaseOfficer implements SettingsOfficerInterface
{
    /** @var Setting[] */
    private $settings = null;

    /** @var SettingManager */
    private $settingManager;

    /** @var ShopHandlerInterface */
    private $shopHandler;

    public function __construct(
        SettingManager $settingManager,
        ShopHandlerInterface $shopHandler = null
    ) {
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

    public function clean()
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
