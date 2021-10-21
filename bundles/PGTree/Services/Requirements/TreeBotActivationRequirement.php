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
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGTree\Services\Requirements;

use Exception;
use PGI\Module\PGFramework\Interfaces\RequirementInterface;
use PGI\Module\PGModule\Services\Settings;

/**
 * Class TreeActivationRequirement
 * @package PGTree\Services\Requirements
 */
class TreeBotActivationRequirement implements RequirementInterface
{
    /** @var Settings */
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function isValid()
    {
        return (bool) $this->settings->get('tree_bot_activated');
    }
}
