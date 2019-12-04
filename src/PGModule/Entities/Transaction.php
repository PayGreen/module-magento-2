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

use Paygreen\Payment\Model\Transaction;

/**
 * Class PGModuleEntitiesTransaction
 *
 * @package PGModule\Entities
 * @method Transaction getLocalEntity()
 */
class PGModuleEntitiesTransaction extends PGDomainFoundationsEntitiesAbstractTransaction
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
    public function setPid($pid)
    {
        $this->getLocalEntity()->setData('pid', (string) $pid);

        return $this;
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
    public function setOrderPrimary($id)
    {
        $this->getLocalEntity()->setData('id_order', (int) $id);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrderState()
    {
        return (string) $this->getLocalEntity()->getData('state');
    }

    /**
     * @inheritdoc
     */
    public function setOrderState($state)
    {
        $this->getLocalEntity()->setData('state', (string) $state);

        return $this;
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
    public function setAmount($amount)
    {
        $this->getLocalEntity()->setData('amount', (int) $amount);

        return $this;
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
    public function setMode($mode)
    {
        $this->getLocalEntity()->setData('mode', (string) $mode);

        return $this;
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

    /**
     * @inheritdoc
     */
    public function setCreatedAt(DateTime $createAt)
    {
        $this->getLocalEntity()->setData('created_at', (int) $createAt->getTimestamp());

        return $this;
    }
}
