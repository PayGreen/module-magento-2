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

namespace PGI\Module\PGTree\Services\Requirements;

use PGI\Module\PGFramework\Interfaces\RequirementInterface;
use PGI\Module\PGFramework\Services\Handlers\RequirementHandler;
use PGI\Module\PGTree\Services\Handlers\TreeAuthenticationHandler;

/**
 * Class TreeConnexionRequirement
 * @package PGTree\Services\Requirements
 */
class TreeConnexionRequirement implements RequirementInterface
{
    /** @var RequirementHandler */
    private $requirementHandler;

    /** @var TreeAuthenticationHandler */
    private $treeAuthenticationHandler;

    public function __construct(
        RequirementHandler $requirementHandler,
        TreeAuthenticationHandler $treeAuthenticationHandler
    ) {
        $this->requirementHandler = $requirementHandler;
        $this->treeAuthenticationHandler = $treeAuthenticationHandler;
    }

    /**
     * @inheritDoc
     */
    public function isFulfilled($arguments = null)
    {
        $isTreeActivated = $this->requirementHandler->isFulfilled('tree_kit_activation', true);

        if ($isTreeActivated) {
            $isTreeConnexionRequired = ($arguments === null) ? true : (bool) $arguments;

            if (!$isTreeConnexionRequired && !$this->treeAuthenticationHandler->isConnected()) {
                return true;
            } elseif ($isTreeConnexionRequired && $this->treeAuthenticationHandler->isConnected()) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }
}
