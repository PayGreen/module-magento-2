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
 * @version   2.0.1
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
    private $orderStatus = null;

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
    public function setOrder($order = null)
    {
        $this->order = $order;

        return $this;
    }

    public function hasOrder()
    {
        return ($this->order !== null);
    }

    /**
     * @return string|null
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * @param string|null $orderStatus
     * @return self
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }
}