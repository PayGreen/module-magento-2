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
 * Class PGPaymentServicesHandlersRefundHandler
 * @package PGPayment\Services\Handlers
 */
class PGPaymentServicesHandlersRefundHandler extends PGSystemFoundationsObject
{
    /** @var PGPaymentServicesManagersTransactionManager */
    private $transactionManager;

    /** @var PGShopServicesManagersOrder */
    private $orderManager;

    /** @var APIPaymentServicesApiFacade */
    private $apiFacade;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(PGPaymentServicesPaygreenFacade $paygreenFacade, PGModuleServicesLogger $logger)
    {
        $this->apiFacade = $paygreenFacade->getApiFacade();
        $this->logger = $logger;
    }

    /**
     * @param PGShopServicesManagersOrder $orderManager
     */
    public function setOrderManager($orderManager)
    {
        $this->orderManager = $orderManager;
    }

    /**
     * @param PGPaymentServicesManagersTransactionManager $transactionManager
     */
    public function setTransactionManager($transactionManager)
    {
        $this->transactionManager = $transactionManager;
    }

    /**
     * @param PGShopInterfacesEntitiesOrder $order
     * @param int $amount
     * @throws PGClientExceptionsResponse
     * @throws PGPaymentExceptionsUnrefundableException
     * @throws Exception
     */
    public function refundOrder(PGShopInterfacesEntitiesOrder $order, $amount = 0)
    {
        /** @var PGPaymentInterfacesEntitiesTransactionInterface $transaction */
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
     * @param PGPaymentInterfacesEntitiesTransactionInterface $transaction
     * @param $amount
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    protected function sendRefundRequest(PGPaymentInterfacesEntitiesTransactionInterface $transaction, $amount)
    {
        /** @var APIPaymentComponentsResponse $apiResponse */
        $apiResponse = $this->apiFacade->refundOrder(
            $transaction->getPid(),
            round($amount, 2)
        );

        if (!$apiResponse->isSuccess()) {
            throw new Exception("Error when refunding transaction with PID '{$transaction->getPid()}'.");
        }
    }

    /**
     * @param PGShopInterfacesEntitiesOrder $order
     * @return PGPaymentInterfacesEntitiesTransactionInterface
     * @throws PGPaymentExceptionsUnrefundableException
     */
    protected function getOrderTransaction(PGShopInterfacesEntitiesOrder $order)
    {
        /** @var PGPaymentInterfacesEntitiesTransactionInterface|null $transaction */
        $transaction = $this->transactionManager->getByOrderPrimary($order->id());

        if ($transaction === null) {
            throw new PGPaymentExceptionsUnrefundableException(
                "Unable to retrieve Paygreen transaction for order #{$order->id()}."
            );
        }

        if ($transaction->getOrderState() === 'REFUND') {
            throw new PGPaymentExceptionsUnrefundableException("Order #{$order->id()} is already refunded.");
        }

        if (!in_array($transaction->getMode(), array('CASH', 'TOKENIZE'))) {
            throw new PGPaymentExceptionsUnrefundableException("Only CASH and TOKENIZE transactions can be refunded.");
        }

        return $transaction;
    }

    protected function updateTransactionOrderState(PGPaymentInterfacesEntitiesTransactionInterface $transaction)
    {
        $transaction->setOrderState('REFUND');

        $this->transactionManager->save($transaction);

        $this->logger->info("Transaction with PID '{$transaction->getPid()}' successfully refund.");
    }
}
