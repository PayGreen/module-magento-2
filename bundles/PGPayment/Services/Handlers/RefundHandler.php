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

namespace PGI\Module\PGPayment\Services\Handlers;

use PGI\Module\APIPayment\Components\Response as ResponseComponent;
use PGI\Module\APIPayment\Services\Facades\ApiFacade;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGPayment\Exceptions\Unrefundable as UnrefundableException;
use PGI\Module\PGPayment\Interfaces\Entities\TransactionEntityInterface;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use PGI\Module\PGPayment\Services\Managers\TransactionManager;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Services\Managers\OrderManager;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;
use Paygreen;

/**
 * Class RefundHandler
 * @package PGPayment\Services\Handlers
 */
class RefundHandler extends AbstractObject
{
    /** @var TransactionManager */
    private $transactionManager;

    /** @var OrderManager */
    private $orderManager;

    /** @var ApiFacade */
    private $apiFacade;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(PaygreenFacade $paygreenFacade, LoggerInterface $logger)
    {
        $this->apiFacade = $paygreenFacade->getApiFacade();
        $this->logger = $logger;
    }

    /**
     * @param OrderManager $orderManager
     */
    public function setOrderManager($orderManager)
    {
        $this->orderManager = $orderManager;
    }

    /**
     * @param TransactionManager $transactionManager
     */
    public function setTransactionManager($transactionManager)
    {
        $this->transactionManager = $transactionManager;
    }

    /**
     * @param OrderEntityInterface $order
     * @param int $amount
     * @throws ResponseException
     * @throws UnrefundableException
     * @throws Exception
     */
    public function refundOrder(OrderEntityInterface $order, $amount = 0)
    {
        /** @var TransactionEntityInterface $transaction */
        $transaction = $this->getOrderTransaction($order);

        $this->logger->info("Execute refund process for PID '{$transaction->getPid()}' and amount '$amount'.");

        $this->sendRefundRequest($transaction, $amount);

        if ($amount > 0) {
            $alreadyRefundedAmount = $this->orderManager->getRefundedAmount($order);

            if ($alreadyRefundedAmount >= $order->getTotalUserAmount()) {
                $this->updateTransactionOrderState($transaction);
            }
        } else {
            $this->updateTransactionOrderState($transaction);
        }
    }

    /**
     * @param TransactionEntityInterface $transaction
     * @param $amount
     * @throws ResponseException
     * @throws Exception
     */
    protected function sendRefundRequest(TransactionEntityInterface $transaction, $amount)
    {
        /** @var ResponseComponent $apiResponse */
        $apiResponse = $this->apiFacade->refundOrder(
            $transaction->getPid(),
            round($amount, 2)
        );

        if (!$apiResponse->isSuccess()) {
            throw new Exception("Error when refunding transaction with PID '{$transaction->getPid()}'.");
        }
    }

    /**
     * @param OrderEntityInterface $order
     * @return TransactionEntityInterface
     * @throws UnrefundableException
     */
    protected function getOrderTransaction(OrderEntityInterface $order)
    {
        /** @var TransactionEntityInterface|null $transaction */
        $transaction = $this->transactionManager->getByOrderPrimary($order->id());

        if ($transaction === null) {
            throw new UnrefundableException(
                "Unable to retrieve Paygreen transaction for order #{$order->id()}."
            );
        }

        if ($transaction->getOrderState() === 'REFUND') {
            throw new UnrefundableException("Order #{$order->id()} is already refunded.");
        }

        if (!in_array($transaction->getMode(), array('CASH', 'TOKENIZE'))) {
            throw new UnrefundableException("Only CASH and TOKENIZE transactions can be refunded.");
        }

        return $transaction;
    }

    protected function updateTransactionOrderState(TransactionEntityInterface $transaction)
    {
        $transaction->setOrderState('REFUND');

        $this->transactionManager->save($transaction);

        $this->logger->info("Transaction with PID '{$transaction->getPid()}' successfully refund.");
    }
}
