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
 * @version   2.1.1
 *
 */

/**
 * Class PGPaymentServicesHandlersTokenizeHandler
 * @package PGPayment\Services\Handlers
 */
class PGPaymentServicesHandlersTokenizeHandler extends PGSystemFoundationsObject
{
    /** @var PGModuleServicesBroadcaster */
    private $broadcaster;

    /** @var PGModuleServicesHandlersBehavior */
    private $behaviorHandler;

    /** @var PGPaymentServicesManagersTransactionManager */
    private $transactionManager;

    /** @var APIPaymentServicesApiFacade */
    private $apiFacade;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(
        PGModuleServicesBroadcaster $broadcaster,
        PGModuleServicesLogger $logger
    ) {
        $this->broadcaster = $broadcaster;
        $this->logger = $logger;
    }

    /**
     * @param PGPaymentServicesManagersTransactionManager $transactionManager
     */
    public function setTransactionManager($transactionManager)
    {
        $this->transactionManager = $transactionManager;
    }

    /**
     * @param PGPaymentServicesPaygreenFacade $paygreenFacade
     */
    public function setPaygreenFacade(PGPaymentServicesPaygreenFacade $paygreenFacade)
    {
        $this->apiFacade = $paygreenFacade->getApiFacade();
    }

    /**
     * @param PGModuleServicesHandlersBehavior $behaviorHandler
     */
    public function setBehaviorHandler($behaviorHandler)
    {
        $this->behaviorHandler = $behaviorHandler;
    }

    /**
     * @param PGShopInterfacesEntitiesOrder $order
     * @return bool
     * @throws Exception
     */
    public function processTokenizedPayments(PGShopInterfacesEntitiesOrder $order)
    {
        $this->logger->debug("Confirm waiting payments for order : '{$order->id()}'.");

        $isTransmissionBehaviorActivated = (bool) $this->behaviorHandler->get('transmission_on_delivery_confirmation');

        if ($isTransmissionBehaviorActivated) {
            if (!$this->transactionManager->hasTransaction($order->id())) {
                $this->logger->warning("No associated transaction found for order '{$order->id()}'.");
                return true;
            }

            $transaction = $this->transactionManager->getByOrderPrimary($order->id());

            if ($transaction->getMode() === PGPaymentData::MODE_TOKENIZE) {
                $this->logger->debug("Tokenized payment validation is running.");

                $pid = $transaction->getPid();

                $result = $this->apiFacade
                    ->validDeliveryPayment($pid)
                    ->isSuccess()
                    ;

                if ($result) {
                    $this->broadcaster->fire(
                        new PGPaymentComponentsEventsTokenizeConfirmationEvent($order, array($transaction))
                    );
                }

                return $result;
            }
        }

        return true;
    }
}
