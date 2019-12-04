<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.2
 */

use Paygreen\Payment\Model\RecurringTransaction;

/**
 * Class PGModuleEntitiesRecurringTransaction
 *
 * @package PGModule\Entities
 * @method RecurringTransaction getLocalEntity()
 */
class PGModuleEntitiesRecurringTransaction extends PGFrameworkFoundationsAbstractEntityWrapped implements PGDomainInterfacesEntitiesRecurringTransactionInterface
{
    /** @var null|PGDomainInterfacesEntitiesOrderInterface */
    private $order = null;

    /**
     * @inheritdoc
     */
    protected function hydrateFromLocalEntity($localEntity)
    {
        // Do nothing.
    }

    /**
     * @inheritdoc
     */
    public function id()
    {
        return (int) $this->getLocalEntity()->getData('id');
    }

    /**
     * @inheritdoc
     */
    public function getPid()
    {
        return (string) $this->getLocalEntity()->getData('pid');
    }

    /**
     * @inheritdoc
     */
    public function getOrderPrimary()
    {
        return (int) $this->getLocalEntity()->getData('id_order');
    }

    /**
     * @inheritdoc
     */
    public function getState()
    {
        return (string) $this->getLocalEntity()->getData('state');
    }

    /**
     * @inheritdoc
     */
    public function getStateOrderBefore()
    {
        return (string) $this->getLocalEntity()->getData('state_order_before');
    }

    /**
     * @inheritdoc
     */
    public function getStateOrderAfter()
    {
        return (string) $this->getLocalEntity()->getData('state_order_after');
    }

    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        if (($this->order === null) && ($this->getOrderPrimary() > 0)) {
            /** @var PGDomainServicesManagersOrderManager $orderManager */
            $orderManager = $this->getService('manager.order');

            $this->order = $orderManager->getByPrimary($this->getOrderPrimary());
        }

        return $this->order;
    }

    /**
     * @inheritdoc
     */
    public function getAmount()
    {
        return (int) $this->getLocalEntity()->getData('amount');
    }

    /**
     * @inheritdoc
     */
    public function getRank()
    {
        return (int) $this->getLocalEntity()->getData('rank');
    }

    /**
     * @inheritdoc
     */
    public function getMode()
    {
        return (string) $this->getLocalEntity()->getData('mode');
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        $dt = new DateTime();

        $dt->setTimestamp((int) $this->getLocalEntity()->getData('created_at'));

        return $dt;
    }
}
