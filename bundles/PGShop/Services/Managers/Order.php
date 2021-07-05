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
 * @version   2.1.0
 *
 */

/**
 * Class PGShopServicesManagersOrder
 *
 * @package PGShop\Services\Managers
 * @method PGShopInterfacesRepositoriesOrder getRepository()
 */
class PGShopServicesManagersOrder extends PGDatabaseFoundationsManager
{
    /** @var PGShopServicesOrderStateMapper */
    protected $orderStateMapper;

    /**
     * @param PGShopServicesOrderStateMapper $orderStateMapper
     */
    public function setOrderStateMapper(PGShopServicesOrderStateMapper $orderStateMapper)
    {
        $this->orderStateMapper = $orderStateMapper;
    }

    /**
     * @param int $id
     * @return PGShopInterfacesEntitiesOrder|null
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    /**
     * @param string $ref
     * @return PGShopInterfacesEntitiesOrder|null
     */
    public function getByReference($ref)
    {
        return $this->getRepository()->findByReference($ref);
    }

    /**
     * @param PGShopInterfacesEntitiesOrder $order
     * @param string $targetState
     * @param string $mode
     * @return bool
     * @throws PGShopExceptionsUnnecessaryOrderTransition
     * @throws PGShopExceptionsUnauthorizedOrderTransition
     * @throws Exception
     * @todo Should not throw an Exception in case of unnecessary transition.
     */
    public function updateOrder(PGShopInterfacesEntitiesOrder $order, $targetState, $mode)
    {
        /** @var PGShopServicesManagersOrderState $orderStateManager */
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
            throw new PGShopExceptionsUnnecessaryOrderTransition($message);
        } else {
            $message = "Unauthorized transition : $currentState -> $targetState";
            throw new PGShopExceptionsUnauthorizedOrderTransition($message);
        }
    }

    /**
     * @param PGShopInterfacesEntitiesOrder $order
     * @throws Exception
     */
    private function fireOrderStateEvent(PGShopInterfacesEntitiesOrder $order)
    {
        /** @var PGModuleServicesBroadcaster $broadcaster */
        $broadcaster = $this->getService('broadcaster');

        $orderEvent = new PGShopComponentsEventsOrderState($order);

        $broadcaster->fire($orderEvent);
    }

    /**
     * @param PGShopInterfacesEntitiesOrder $order
     * @return int
     */
    public function getRefundedAmount(PGShopInterfacesEntitiesOrder $order)
    {
        return $this->getRepository()->findRefundedAmount($order);
    }
}
