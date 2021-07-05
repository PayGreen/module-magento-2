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
 * Class PGPaymentComponentsTasksTransactionManagement
 * @package PGPayment\Components\Tasks
 */
class PGPaymentComponentsTasksTransactionManagement extends PGFrameworkFoundationsTask
{
    const STATE_PAYMENT_REFUSED = 11;
    const STATE_ORDER_CANCELED = 12;
    const STATE_ORDER_NOT_FOUND = 13;
    const STATE_WORKFLOW_ERROR = 14;
    const STATE_UNNECESSARY_TASK = 15;

    /** @var PGShopInterfacesProvisionersPostPayment */
    private $provisioner;

    /** @var PGShopInterfacesEntitiesOrder|null  */
    private $order = null;

    /** @var string|null */
    private $orderStateFrom = null;

    /** @var string|null */
    private $orderStateTo = null;

    public function __construct(PGShopInterfacesProvisionersPostPayment $provisioner)
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
     * @return APIPaymentComponentsRepliesTransaction
     */
    public function getTransaction()
    {
        return $this->provisioner->getTransaction();
    }

    /**
     * @return PGShopInterfacesProvisionersPostPayment
     */
    public function getProvisioner()
    {
        return $this->provisioner;
    }

    /**
     * @return PGShopInterfacesEntitiesOrder|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param PGShopInterfacesEntitiesOrder|null $order
     * @return self
     */
    public function setOrder(PGShopInterfacesEntitiesOrder $order = null)
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
