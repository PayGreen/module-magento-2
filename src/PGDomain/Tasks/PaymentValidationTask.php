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
 * @version   0.3.5
 */

/**
 * Class PGDomainTasksPaymentValidationTask
 * @package PGDomain\Tasks
 */
class PGDomainTasksPaymentValidationTask extends PGFrameworkFoundationsAbstractTask
{
    const STATE_PID_LOCKED = 11;
    const STATE_PAYMENT_ABORTED = 12;
    const STATE_PAYMENT_REFUSED = 13;
    const STATE_INCONSISTENT_CONTEXT = 15;
    const STATE_PAYGREEN_UNAVAILABLE = 16;
    const STATE_WORKFLOW_ERROR = 17;
    const STATE_PROVIDER_ERROR = 18;
    const STATE_PID_NOT_FOUND = 19;

    /** @var string */
    private $pid;

    /** @var PGClientEntitiesPaygreenTransaction */
    private $transaction;

    /** @var PGDomainInterfacesEntitiesOrderInterface|null  */
    private $order = null;

    /** @var PGDomainInterfacesPostPaymentProvisionerInterface|null */
    private $postPaymentProvisioner = null;

    public function __construct($pid)
    {
        $this->pid = $pid;
    }

    public function getName()
    {
        return 'PaymentValidation';
    }

    /**
     * @return string
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @return PGClientEntitiesPaygreenTransaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param PGClientEntitiesPaygreenTransaction $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
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

    /**
     * @return PGDomainInterfacesPostPaymentProvisionerInterface|null
     */
    public function getProvisioner()
    {
        return $this->postPaymentProvisioner;
    }

    /**
     * @param PGDomainInterfacesPostPaymentProvisionerInterface|null $postPaymentProvisioner
     */
    public function setProvisioner($postPaymentProvisioner)
    {
        $this->postPaymentProvisioner = $postPaymentProvisioner;
    }
}
