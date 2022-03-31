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
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGModule\Services\Upgrades;

use PGI\Module\PGModule\Components\UpgradeStage as UpgradeComponent;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;
use PGI\Module\PGModule\Services\Settings;

/**
 * Class UpdateSettingsValuesUpgrade
 * @package PGModule\Services\Upgrades
 */
class UpdateSettingsValuesUpgrade implements UpgradeInterface
{
    /** @var Settings */
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     */
    public function apply(UpgradeComponent $upgradeStage)
    {
        $key_to_update = $upgradeStage->getConfig('key_to_update');
        $values = $upgradeStage->getConfig('values');

        $settingCurrentValue = $this->settings->get($key_to_update);

        if (array_key_exists($settingCurrentValue, $values)) {
            $this->settings->set($key_to_update, $values[$settingCurrentValue]);
        }
    }
}
