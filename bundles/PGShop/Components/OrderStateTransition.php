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

namespace PGI\Module\PGShop\Components;

/**
 * Class OrderStateTransition
 * @package PGShop\Components
 */
class OrderStateTransition
{
    /** @var string */
    private $currentState;

    /** @var string */
    private $targetState;

    public function __construct($currentState, $targetState)
    {
        $this->currentState = $currentState;
        $this->targetState = $targetState;
    }

    /**
     * @return string
     */
    public function getCurrentState()
    {
        return $this->currentState;
    }

    /**
     * @return string
     */
    public function getTargetState()
    {
        return $this->targetState;
    }

    /**
     * @param string $targetState
     */
    public function setTargetState($targetState)
    {
        $this->targetState = $targetState;
    }
}
