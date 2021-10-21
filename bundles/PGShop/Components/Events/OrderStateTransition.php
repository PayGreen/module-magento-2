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

namespace PGI\Module\PGShop\Components\Events;

use PGI\Module\PGModule\Foundations\AbstractEvent;
use PGI\Module\PGShop\Components\OrderStateTransition as OrderStateTransitionComponent;

/**
 * Class OrderStateTransition
 * @package PGShop\Components\Events
 */
class OrderStateTransition extends AbstractEvent
{
    /** @var OrderStateTransitionComponent */
    private $orderStateTransition;

    public function __construct(OrderStateTransitionComponent $orderStateTransition)
    {
        $this->orderStateTransition = $orderStateTransition;
    }

    /**
     * @return string
     */
    public function getName()
    {
        $currentState = $this->orderStateTransition->getCurrentState();
        $targetState = $this->orderStateTransition->getTargetState();

        return 'ORDER.STATE_TRANSITION.' . strtoupper($currentState) . '.' . strtoupper($targetState);
    }

    /**
     * @return OrderStateTransitionComponent
     */
    public function getOrderStateTransition()
    {
        return $this->orderStateTransition;
    }
}
