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

class PGDomainEntitiesTransaction extends PGFrameworkFoundationsAbstractEntityPersisted implements PGDomainInterfacesEntitiesTransactionInterface
{
    /** @var PGDomainInterfacesEntitiesOrderInterface */
    private $order = null;

    /**
     * @return string
     */
    public function getPid()
    {
        return $this->get('pid');
    }

    public function setPid($pid)
    {
        return $this->set('pid', $pid);
    }

    /**
     * @return int
     */
    public function getOrderPrimary()
    {
        return $this->get('id_order');
    }

    public function setOrderPrimary($id)
    {
        return $this->set('id_order', $id);
    }

    public function getOrder()
    {
        if (($this->order === null) && ($this->getOrderPrimary() > 0)) {
            $this->loadOrder();
        }

        return $this->order;
    }

    protected function loadOrder()
    {
        /** @var PGDomainServicesManagersOrderManager $orderManager */
        $orderManager = $this->getService('manager.order');

        $id_order = $this->getOrderPrimary();

        $this->order = $orderManager->getByPrimary($id_order);
    }

    public function setOrder(PGDomainInterfacesEntitiesOrderInterface $order)
    {
        $this->order = $order;

        return $this->setOrderPrimary($order->id());
    }

    public function getOrderState()
    {
        return $this->get('state');
    }

    public function setOrderState($state)
    {
        return $this->set('state', $state);
    }

    public function getMode()
    {
        return $this->get('mode');
    }

    public function setMode($mode)
    {
        return $this->set('mode', $mode);
    }

    public function getAmount()
    {
        return (int) $this->get('amount');
    }

    public function setAmount($amount)
    {
        return $this->set('amount', $amount);
    }

    public function getCreatedAt()
    {
        $timestamp = (int) $this->get('created_at');

        $dt = new DateTime();

        return $dt->setTimestamp($timestamp);
    }

    public function setCreatedAt(DateTime $createAt)
    {
        return $this->set('created_at', $createAt->getTimestamp());
    }
}
