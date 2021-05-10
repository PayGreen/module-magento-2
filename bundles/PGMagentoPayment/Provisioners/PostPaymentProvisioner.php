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
 * @version   2.0.2
 *
 */

/**
 * Class PGMagentoPaymentProvisionersPostPaymentProvisioner
 * @package PGMagentoPayment\Provisioners
 */
class PGMagentoPaymentProvisionersPostPaymentProvisioner extends PGSystemFoundationsObject implements PGShopInterfacesProvisionersPostPayment
{
    /** @var string */
    private $pid;

    /** @var APIPaymentComponentsRepliesTransaction */
    private $transaction;

    /** @var PGShopInterfacesEntitiesOrder */
    private $order;

    /**
     * PGShopInterfacesProvisionersPostPayment constructor.
     * @param string $pid
     * @param APIPaymentComponentsRepliesTransaction $transaction
     * @throws Exception
     */
    public function __construct($pid, APIPaymentComponentsRepliesTransaction $transaction)
    {
        $this->pid = $pid;
        $this->transaction = $transaction;

        $this->loadOrder();
    }

    protected function loadOrder()
    {
        /** @var PGShopServicesManagersOrder $orderManager */
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
     * @return APIPaymentComponentsRepliesTransaction
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
