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
 * Class PGDomainTasksTransactionManagementTask
 * @package PGDomain\Tasks
 */
class PGDomainTasksTransactionManagementTask extends PGFrameworkFoundationsAbstractTask
{
    const STATE_PAYMENT_REFUSED = 11;
    const STATE_ORDER_CANCELED = 12;
    const STATE_ORDER_NOT_FOUND = 13;
    const STATE_WORKFLOW_ERROR = 14;
    const STATE_UNNECESSARY_TASK = 15;

    /** @var PGDomainInterfacesPostPaymentProvisionerInterface */
    private $provisioner;

    /** @var PGDomainInterfacesEntitiesOrderInterface|null  */
    private $order = null;

    /** @var string|null */
    private $orderStatus = null;

    public function __construct(PGDomainInterfacesPostPaymentProvisionerInterface $provisioner)
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
     * @return PGClientEntitiesPaygreenTransaction
     */
    public function getTransaction()
    {
        return $this->provisioner->getTransaction();
    }

    /**
     * @return PGDomainInterfacesPostPaymentProvisionerInterface
     */
    public function getProvisioner()
    {
        return $this->provisioner;
    }

    /**
     * @return PGDomainInterfacesEntitiesOrderInterface|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param PGDomainInterfacesEntitiesOrderInterface|null $order
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
