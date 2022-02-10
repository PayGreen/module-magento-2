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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGPayment\Components\Tasks;

use PGI\Module\APIPayment\Components\Replies\Transaction as TransactionReplyComponent;
use PGI\Module\PGFramework\Foundations\AbstractTask;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Interfaces\Provisioners\PostPaymentProvisionerInterface;

/**
 * Class TransactionManagement
 * @package PGPayment\Components\Tasks
 */
class TransactionManagement extends AbstractTask
{
    const STATE_PAYMENT_REFUSED = 11;
    const STATE_ORDER_CANCELED = 12;
    const STATE_ORDER_NOT_FOUND = 13;
    const STATE_WORKFLOW_ERROR = 14;
    const STATE_UNNECESSARY_TASK = 15;

    /** @var PostPaymentProvisionerInterface */
    private $provisioner;

    /** @var OrderEntityInterface|null  */
    private $order = null;

    /** @var string|null */
    private $orderStateFrom = null;

    /** @var string|null */
    private $orderStateTo = null;

    public function __construct(PostPaymentProvisionerInterface $provisioner)
    {
        $this->provisioner = $provisioner;
    }

    public function getName()
    {
        return 'TransactionManagement';
    }

    /**
     * @return string
     */
    public function getPid()
    {
        return $this->provisioner->getPid();
    }

    /**
     * @return TransactionReplyComponent
     */
    public function getTransaction()
    {
        return $this->provisioner->getTransaction();
    }

    /**
     * @return PostPaymentProvisionerInterface
     */
    public function getProvisioner()
    {
        return $this->provisioner;
    }

    /**
     * @return OrderEntityInterface|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param OrderEntityInterface|null $order
     * @return self
     */
    public function setOrder(OrderEntityInterface $order = null)
    {
        $this->order = $order;

        if (($this->orderStateTo === null) && ($order !== null)) {
            $this->orderStateTo = $order->getState();
        }

        return $this;
    }

    public function hasOrder()
    {
        return ($this->order !== null);
    }

    /**
     * @return string|null
     */
    public function getOrderStateTo()
    {
        return $this->orderStateTo;
    }

    /**
     * @param string|null $orderStateTo
     * @return self
     */
    public function setOrderStateTo($orderStateTo)
    {
        $this->orderStateTo = $orderStateTo;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrderStateFrom()
    {
        return $this->orderStateFrom;
    }

    /**
     * @param null $orderStateFrom
     * @return self
     */
    public function setOrderStateFrom($orderStateFrom)
    {
        $this->orderStateFrom = $orderStateFrom;

        return $this;
    }
}
