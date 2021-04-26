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
 * @version   2.0.0
 *
 */

/**
 * Class PGPaymentEntitiesRecurringTransaction
 * @package PGPayment\Entities
 */
class PGPaymentEntitiesRecurringTransaction extends PGDatabaseFoundationsEntityPersisted implements PGPaymentInterfacesEntitiesRecurringTransactionInterface
{
    /** @var PGShopInterfacesEntitiesOrder */
    private $order = null;

    /**
     * @inheritdoc
     */
    public function getPid()
    {
        return $this->get('pid');
    }

    /**
     * @inheritdoc
     */
    public function getOrderPrimary()
    {
        return $this->get('id_order');
    }

    /**
     * @inheritdoc
     */
    public function getState()
    {
        return $this->get('state');
    }

    /**
     * @inheritdoc
     */
    public function getStateOrderBefore()
    {
        return $this->get('state_order_before');
    }

    /**
     * @inheritdoc
     */
    public function getStateOrderAfter()
    {
        return $this->get('state_order_after');
    }

    public function setStateOrderAfter($stateOrderAfter)
    {
        return $this->set('state_order_after', $stateOrderAfter);
    }

    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        if (($this->order === null) && ($this->getOrderPrimary() > 0)) {
            $this->loadOrder();
        }

        return $this->order;
    }

    protected function loadOrder()
    {
        /** @var PGShopServicesManagersOrder $orderManager */
        $orderManager = $this->getService('manager.order');

        $id_order = $this->getOrderPrimary();

        $this->order = $orderManager->getByPrimary($id_order);
    }

    /**
     * @inheritdoc
     */
    public function getAmount()
    {
        return $this->get('amount');
    }

    /**
     * @inheritdoc
     */
    public function getRank()
    {
        return $this->get('rank');
    }

    /**
     * @inheritdoc
     */
    public function getMode()
    {
        return $this->get('mode');
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        $timestamp = (int) $this->get('created_at');

        $dt = new DateTime();

        return $dt->setTimestamp($timestamp);
    }
}
