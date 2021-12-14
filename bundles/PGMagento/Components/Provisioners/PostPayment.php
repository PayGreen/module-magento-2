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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGMagento\Components\Provisioners;

use PGI\Module\APIPayment\Components\Replies\Transaction as TransactionReplyComponent;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Interfaces\Provisioners\PostPaymentProvisionerInterface;
use PGI\Module\PGShop\Services\Managers\OrderManager;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class PostPayment
 * @package PGMagento\Components\Provisioners
 */
class PostPayment extends AbstractObject implements PostPaymentProvisionerInterface
{
    /** @var string */
    private $pid;

    /** @var TransactionReplyComponent */
    private $transaction;

    /** @var OrderEntityInterface */
    private $order;

    /**
     * PostPaymentProvisionerInterface constructor.
     * @param string $pid
     * @param TransactionReplyComponent $transaction
     * @throws Exception
     */
    public function __construct($pid, TransactionReplyComponent $transaction)
    {
        $this->pid = $pid;
        $this->transaction = $transaction;

        $this->loadOrder();
    }

    protected function loadOrder()
    {
        /** @var OrderManager $orderManager */
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
     * @return TransactionReplyComponent
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
