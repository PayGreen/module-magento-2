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
 * Class PGPaymentServicesListenersRefundListener
 * @package PGPayment\Services\Listeners
 */
class PGPaymentServicesListenersRefundListener
{
    /** @var PGPaymentServicesHandlersRefundHandler */
    private $refundHandler;

    /** @var PGModuleServicesHandlersBehavior */
    private $behaviorHandler;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(
        PGPaymentServicesHandlersRefundHandler $refundHandler,
        PGModuleServicesHandlersBehavior $behaviorHandler,
        PGModuleServicesLogger $logger
    ) {
        $this->refundHandler = $refundHandler;
        $this->behaviorHandler = $behaviorHandler;
        $this->logger = $logger;
    }

    public function listen(PGPaymentComponentsEventsRefundEvent $event)
    {
        $this->logger->debug("Refund event catched.");

        try {
            $isRefundActivated = (bool) $this->behaviorHandler->get('transmission_on_refund');

            if ($isRefundActivated) {
                $this->logger->debug("Online refund activated.");

                $this->refundHandler->refundOrder($event->getOrder(), $event->getAmount());
            }
        } catch (PGPaymentExceptionsUnrefundableException $exception) {
            $this->logger->notice(
                "Order #{$event->getOrder()->id()} is not refundable : " . $exception->getMessage()
            );
        } catch (Exception $exception) {
            $this->logger->error(
                "Error during refund order #{$event->getOrder()->id()} : " . $exception->getMessage(),
                $exception
            );
        }
    }
}
