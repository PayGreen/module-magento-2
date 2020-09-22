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
 * @version   1.1.1
 */

/**
 * Class PGDomainServicesHandlersRefundHandler
 * @package PGFramework\Services\Handlers
 */
class PGDomainServicesHandlersRefundHandler extends PGFrameworkFoundationsAbstractObject
{
    /** @var PGDomainServicesManagersTransactionManager */
    private $transactionManager;

    /** @var PGDomainServicesManagersOrderManager */
    private $orderManager;

    /** @var PGClientServicesApiFacade */
    private $apiFacade;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    public function __construct(PGDomainServicesPaygreenFacade $paygreenFacade, PGFrameworkServicesLogger $logger)
    {
        $this->apiFacade = $paygreenFacade->getApiFacade();
        $this->logger = $logger;
    }

    /**
     * @param PGDomainServicesManagersOrderManager $orderManager
     */
    public function setOrderManager($orderManager)
    {
        $this->orderManager = $orderManager;
    }

    /**
     * @param PGDomainServicesManagersTransactionManager $transactionManager
     */
    public function setTransactionManager($transactionManager)
    {
        $this->transactionManager = $transactionManager;
    }

    /**
     * @param PGDomainInterfacesEntitiesOrderInterface $order
     * @param int $amount
     * @throws PGClientExceptionsPaymentRequestException
     * @throws PGDomainExceptionsUnrefundableException
     * @throws Exception
     */
    public function refundOrder(PGDomainInterfacesEntitiesOrderInterface $order, $amount = 0)
    {
        /** @var PGDomainInterfacesEntitiesTransactionInterface $transaction */
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
     * @param PGDomainInterfacesEntitiesTransactionInterface $transaction
     * @param $amount
     * @throws PGClientExceptionsPaymentRequestException
     * @throws Exception
     */
    protected function sendRefundRequest(PGDomainInterfacesEntitiesTransactionInterface $transaction, $amount)
    {
        /** @var PGClientEntitiesResponse $apiResponse */
        $apiResponse = $this->apiFacade->refundOrder(
            $transaction->getPid(),
            round($amount, 2)
        );

        if (!$apiResponse->isSuccess()) {
            throw new Exception("Error when refunding transaction with PID '{$transaction->getPid()}'.");
        }
    }

    /**
     * @param PGDomainInterfacesEntitiesOrderInterface $order
     * @return PGDomainInterfacesEntitiesTransactionInterface
     * @throws PGDomainExceptionsUnrefundableException
     */
    protected function getOrderTransaction(PGDomainInterfacesEntitiesOrderInterface $order)
    {
        /** @var PGDomainInterfacesEntitiesTransactionInterface|null $transaction */
        $transaction = $this->transactionManager->getByOrderPrimary($order->id());

        if ($transaction === null) {
            throw new PGDomainExceptionsUnrefundableException("Unable to retrieve Paygreen transaction for order #{$order->id()}.");
        }

        if ($transaction->getOrderState() === 'REFUND') {
            throw new PGDomainExceptionsUnrefundableException("Order #{$order->id()} is already refunded.");
        }

        if (!in_array($transaction->getMode(), array('CASH', 'TOKENIZE'))) {
            throw new PGDomainExceptionsUnrefundableException("Only CASH and TOKENIZE transactions can be refunded.");
        }

        return $transaction;
    }

    protected function updateTransactionOrderState(PGDomainInterfacesEntitiesTransactionInterface $transaction)
    {
        $transaction->setOrderState('REFUND');

        $this->transactionManager->save($transaction);

        $this->logger->info("Transaction with PID '{$transaction->getPid()}' successfully refund.");
    }
}
