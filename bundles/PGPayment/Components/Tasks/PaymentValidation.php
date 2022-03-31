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
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGPayment\Components\Tasks;

use PGI\Module\APIPayment\Components\Replies\Transaction as TransactionReplyComponent;
use PGI\Module\PGFramework\Foundations\AbstractTask;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Interfaces\Provisioners\PostPaymentProvisionerInterface;

/**
 * Class PaymentValidation
 * @package PGPayment\Components\Tasks
 */
class PaymentValidation extends AbstractTask
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

    /** @var TransactionReplyComponent */
    private $transaction;

    /** @var OrderEntityInterface|null  */
    private $order = null;

    /** @var string|null */
    private $finalOrderState = null;

    /** @var PostPaymentProvisionerInterface|null */
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
     * @return TransactionReplyComponent
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param TransactionReplyComponent $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
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
    public function setOrder($order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFinalOrderState()
    {
        return $this->finalOrderState;
    }

    /**
     * @param string $finalOrderState
     */
    public function setFinalOrderState($finalOrderState)
    {
        $this->finalOrderState = $finalOrderState;
    }

    /**
     * @return PostPaymentProvisionerInterface|null
     */
    public function getProvisioner()
    {
        return $this->postPaymentProvisioner;
    }

    /**
     * @param PostPaymentProvisionerInterface|null $postPaymentProvisioner
     */
    public function setProvisioner($postPaymentProvisioner)
    {
        $this->postPaymentProvisioner = $postPaymentProvisioner;
    }
}
