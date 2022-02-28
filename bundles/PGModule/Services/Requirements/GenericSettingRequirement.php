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
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGModule\Services\Requirements;

use Exception;
use PGI\Module\PGFramework\Foundations\AbstractRequirement;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGSystem\Exceptions\Configuration;

/**
 * Class GenericSettingRequirement
 * @package PGModule\Services\Requirements
 */
class GenericSettingRequirement extends AbstractRequirement
{
    /** @var Settings */
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     * @throws Configuration
     * @throws Exception
     */
    public function isValid()
    {
        $key = $this->getConfig('setting');

        if (!$key) {
            throw new Configuration("GenericSettingRequirement require 'setting' config parameter.");
        }

        return (bool) $this->settings->get($key);
    }
}
