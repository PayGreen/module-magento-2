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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGTree\Services\Requirements;

use PGI\Module\PGFramework\Interfaces\RequirementInterface;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGTree\Services\Requirements\TreeActivationRequirement;

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
     */
    public function isFulfilled($arguments = null)
    {
        $isTreeBotActive = (bool) $this->settings->get('tree_bot_activated');

        $isTreeBotActivationRequired = ($arguments === null) ? true : (bool) $arguments;

        if (!$isTreeBotActivationRequired && !$isTreeBotActive) {
            return true;
        } elseif ($isTreeBotActivationRequired && $isTreeBotActive) {
            return true;
        } else {
            return false;
        }
    }
}
