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

namespace PGI\Module\PGShop\Services\Managers;

use PGI\Module\PGDatabase\Foundations\AbstractManager;
use PGI\Module\PGModule\Services\Broadcaster;
use PGI\Module\PGShop\Components\Events\OrderState as OrderStateEventComponent;
use PGI\Module\PGShop\Exceptions\UnauthorizedOrderTransition as UnauthorizedOrderTransitionException;
use PGI\Module\PGShop\Exceptions\UnnecessaryOrderTransition as UnnecessaryOrderTransitionException;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Interfaces\Repositories\OrderRepositoryInterface;
use PGI\Module\PGShop\Services\Managers\OrderStateManager;
use PGI\Module\PGShop\Services\Mappers\OrderStateMapper;
use Exception;

/**
 * Class OrderManager
 *
 * @package PGShop\Services\Managers
 * @method OrderRepositoryInterface getRepository()
 */
class OrderManager extends AbstractManager
{
    /** @var OrderStateMapper */
    protected $orderStateMapper;

    /**
     * @param OrderStateMapper $orderStateMapper
     */
    public function setOrderStateMapper(OrderStateMapper $orderStateMapper)
    {
        $this->orderStateMapper = $orderStateMapper;
    }

    /**
     * @param int $id
     * @return OrderEntityInterface|null
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    /**
     * @param string $ref
     * @return OrderEntityInterface|null
     */
    public function getByReference($ref)
    {
        return $this->getRepository()->findByReference($ref);
    }

    /**
     * @param OrderEntityInterface $order
     * @param string $targetState
     * @param string $mode
     * @return bool
     * @throws UnnecessaryOrderTransitionException
     * @throws UnauthorizedOrderTransitionException
     * @throws Exception
     * @todo Should not throw an Exception in case of unnecessary transition.
     */
    public function updateOrder(OrderEntityInterface $order, $targetState, $mode)
    {
        /** @var OrderStateManager $orderStateManager */
        $orderStateManager = $this->getService('manager.order_state');

        $currentState = $order->getState();

        if ($orderStateManager->isAllowedTransition($mode, $currentState, $targetState)) {
            $this->getService('logger')->debug(
                'updateOrderStatus : '. $currentState . ' -> ' . $targetState
            );

            $targetLocalState = $this->orderStateMapper->getLocalOrderState($targetState);

            $result = $this->getRepository()->updateOrderState($order, $targetLocalState);

            if (!$result) {
                throw new Exception("Unable to update state for order #{$order->id()}.");
            }

            $this->fireOrderStateEvent($order);

            return (bool) $result;
        } elseif ($currentState === $targetState) {
            $message = "Unnecessary transition : $currentState -> $targetState";
            throw new UnnecessaryOrderTransitionException($message);
        } else {
            $message = "Unauthorized transition : $currentState -> $targetState";
            throw new UnauthorizedOrderTransitionException($message);
        }
    }

    /**
     * @param OrderEntityInterface $order
     * @throws Exception
     */
    private function fireOrderStateEvent(OrderEntityInterface $order)
    {
        /** @var Broadcaster $broadcaster */
        $broadcaster = $this->getService('broadcaster');

        $orderEvent = new OrderStateEventComponent($order);

        $broadcaster->fire($orderEvent);
    }

    /**
     * @param OrderEntityInterface $order
     * @return int
     */
    public function getRefundedAmount(OrderEntityInterface $order)
    {
        return $this->getRepository()->findRefundedAmount($order);
    }
}
