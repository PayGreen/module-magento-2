<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.1
 */

/**
 * Class PGDomainServicesManagersOrderManager
 *
 * @package PGDomain\Services\Managers
 * @method PGDomainInterfacesRepositoriesOrderRepositoryInterface getRepository()
 */
class PGDomainServicesManagersOrderManager extends PGFrameworkFoundationsAbstractManager
{
    /** @var PGDomainServicesOrderStateMapper */
    protected $orderStateMapper;

    /**
     * @param PGDomainServicesOrderStateMapper $orderStateMapper
     */
    public function setOrderStateMapper(PGDomainServicesOrderStateMapper $orderStateMapper)
    {
        $this->orderStateMapper = $orderStateMapper;
    }

    /**
     * @param int $id
     * @return PGDomainInterfacesEntitiesOrderInterface|null
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    /**
     * @param string $ref
     * @return PGDomainInterfacesEntitiesOrderInterface|null
     */
    public function getByReference($ref)
    {
        return $this->getRepository()->findByReference($ref);
    }

    /**
     * @param PGDomainInterfacesEntitiesOrderInterface $order
     * @param string $targetState
     * @param string $mode
     * @return bool
     * @throws PGDomainExceptionsUnnecessaryOrderTransitionException
     * @throws PGDomainExceptionsUnauthorizedOrderTransitionException
     * @throws Exception
     * @todo Should not throw an Exception in case of unnecessary transition.
     */
    public function updateOrder(PGDomainInterfacesEntitiesOrderInterface $order, $targetState, $mode)
    {
        /** @var PGDomainServicesManagersOrderStateManager $orderStateManager */
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
            throw new PGDomainExceptionsUnnecessaryOrderTransitionException($message);
        } else {
            $message = "Unauthorized transition : $currentState -> $targetState";
            throw new PGDomainExceptionsUnauthorizedOrderTransitionException($message);
        }
    }

    /**
     * @param PGDomainInterfacesEntitiesOrderInterface $order
     * @throws Exception
     */
    private function fireOrderStateEvent(PGDomainInterfacesEntitiesOrderInterface $order)
    {
        /** @var PGFrameworkServicesBroadcaster $broadcaster */
        $broadcaster = $this->getService('broadcaster');

        $orderEvent = new PGDomainComponentsEventsOrderStateEvent($order);

        $broadcaster->fire($orderEvent);
    }

    /**
     * @param PGDomainInterfacesEntitiesOrderInterface $order
     * @return int
     */
    public function getRefundedAmount(PGDomainInterfacesEntitiesOrderInterface $order)
    {
        return $this->getRepository()->findRefundedAmount($order);
    }
}
