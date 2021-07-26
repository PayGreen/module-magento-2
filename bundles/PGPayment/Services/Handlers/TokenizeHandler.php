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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGPayment\Services\Handlers;

use PGI\Module\APIPayment\Services\Facades\ApiFacade;
use PGI\Module\PGModule\Services\Broadcaster;
use PGI\Module\PGModule\Services\Handlers\BehaviorHandler;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGPayment\Components\Events\TokenizeConfirmation as TokenizeConfirmationEventComponent;
use PGI\Module\PGPayment\Data;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use PGI\Module\PGPayment\Services\Managers\TransactionManager;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class TokenizeHandler
 * @package PGPayment\Services\Handlers
 */
class TokenizeHandler extends AbstractObject
{
    /** @var Broadcaster */
    private $broadcaster;

    /** @var BehaviorHandler */
    private $behaviorHandler;

    /** @var TransactionManager */
    private $transactionManager;

    /** @var ApiFacade */
    private $apiFacade;

    /** @var Logger */
    private $logger;

    public function __construct(
        Broadcaster $broadcaster,
        Logger $logger
    ) {
        $this->broadcaster = $broadcaster;
        $this->logger = $logger;
    }

    /**
     * @param TransactionManager $transactionManager
     */
    public function setTransactionManager($transactionManager)
    {
        $this->transactionManager = $transactionManager;
    }

    /**
     * @param PaygreenFacade $paygreenFacade
     */
    public function setPaygreenFacade(PaygreenFacade $paygreenFacade)
    {
        $this->apiFacade = $paygreenFacade->getApiFacade();
    }

    /**
     * @param BehaviorHandler $behaviorHandler
     */
    public function setBehaviorHandler($behaviorHandler)
    {
        $this->behaviorHandler = $behaviorHandler;
    }

    /**
     * @param OrderEntityInterface $order
     * @return bool
     * @throws Exception
     */
    public function processTokenizedPayments(OrderEntityInterface $order)
    {
        $this->logger->debug("Confirm waiting payments for order : '{$order->id()}'.");

        $isTransmissionBehaviorActivated = (bool) $this->behaviorHandler->get('transmission_on_delivery_confirmation');

        if ($isTransmissionBehaviorActivated) {
            if (!$this->transactionManager->hasTransaction($order->id())) {
                $this->logger->warning("No associated transaction found for order '{$order->id()}'.");
                return true;
            }

            $transaction = $this->transactionManager->getByOrderPrimary($order->id());

            if ($transaction->getMode() === Data::MODE_TOKENIZE) {
                $this->logger->debug("Tokenized payment validation is running.");

                $pid = $transaction->getPid();

                $result = $this->apiFacade
                    ->validDeliveryPayment($pid)
                    ->isSuccess()
                    ;

                if ($result) {
                    $this->broadcaster->fire(
                        new TokenizeConfirmationEventComponent($order, array($transaction))
                    );
                }

                return $result;
            }
        }

        return true;
    }
}
