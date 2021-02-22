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
 * @version   1.2.4
 *
 */

/**
 * Class PGModuleProvisionersPostPaymentProvisioner
 * @package PGModule\Provisioners
 */
class PGModuleProvisionersPostPaymentProvisioner extends PGFrameworkFoundationsAbstractObject implements PGDomainInterfacesPostPaymentProvisionerInterface
{
    /** @var string */
    private $pid;

    /** @var PGClientEntitiesPaygreenTransaction */
    private $transaction;

    /** @var PGDomainInterfacesEntitiesOrderInterface */
    private $order;

    /**
     * PGDomainInterfacesPostPaymentProvisioner constructor.
     * @param string $pid
     * @param PGClientEntitiesPaygreenTransaction $transaction
     * @throws Exception
     */
    public function __construct($pid, PGClientEntitiesPaygreenTransaction $transaction)
    {
        $this->pid = $pid;
        $this->transaction = $transaction;

        $this->loadOrder();
    }

    protected function loadOrder()
    {
        /** @var PGDomainServicesManagersOrderManager $orderManager */
        $orderManager = $this->getService('manager.order');

        $ref = (int) $this->transaction->getOrderPrimary();

        $order = $orderManager->getByPrimary($ref);

        if ($order === null) {
            throw new Exception("Order #$ref not found.");
        }

        $this->order = $order;
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
     * @return float
     */
    public function getUserAmount()
    {
        return $this->order->getTotalUserAmount();
    }
}
