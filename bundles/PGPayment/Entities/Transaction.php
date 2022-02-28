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

namespace PGI\Module\PGPayment\Entities;

use PGI\Module\PGDatabase\Foundations\AbstractEntityPersisted;
use PGI\Module\PGPayment\Interfaces\Entities\TransactionEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Services\Managers\OrderManager;
use DateTime;

/**
 * Class Transaction
 * @package PGPayment\Entities
 */
class Transaction extends AbstractEntityPersisted implements TransactionEntityInterface
{
    /** @var OrderEntityInterface */
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
        /** @var OrderManager $orderManager */
        $orderManager = $this->getService('manager.order');

        $id_order = $this->getOrderPrimary();

        $this->order = $orderManager->getByPrimary($id_order);
    }

    public function setOrder(OrderEntityInterface $order)
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
