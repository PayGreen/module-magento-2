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

namespace PGI\Module\PGMagentoPayment\Services\Listeners;

use Magento\Framework\App\ObjectManager as LocalObjectManager;
use Magento\Framework\DB\Transaction as LocalTransaction;
use Magento\Sales\Model\Order as LocalOrder;
use Magento\Sales\Model\Order\Invoice as LocalInvoice;
use Magento\Sales\Model\Service\InvoiceService as LocalInvoiceService;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender as LocalInvoiceSender;
use Magento\Framework\Exception\LocalizedException as LocalLocalizedException;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGShop\Components\Events\Order as OrderEventComponent;
use Exception;

class OrderValidationListener
{
    /** @var LocalObjectManager */
    private $magento;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(LocalObjectManager $magento, LoggerInterface $logger)
    {
        $this->magento = $magento;
        $this->logger = $logger;
    }

    public function saveOrderHistory(OrderEventComponent $event)
    {
        /** @var LocalOrder $localOrder */
        $localOrder = $event->getOrder()->getLocalEntity();

        $state = $localOrder->getState();
        $status = $localOrder->getStatus();

        $this->logger->info("Save validation history log ($state/$status) for order #{$localOrder->getId()}.");

        $localOrder->addStatusToHistory($localOrder->getStatus(), "Order processed successfully with PID #{$event->getPid()}");

        $localOrder->save();
    }

    /**
     * @param OrderEventComponent $event
     * @throws LocalLocalizedException
     * @throws Exception
     */
    public function createInvoice(OrderEventComponent $event)
    {
        /** @var LocalOrder $localOrder */
        $localOrder = $event->getOrder()->getLocalEntity();

        if($localOrder->canInvoice()) {
            $this->logger->info("Generate invoice for transaction with PID #{$event->getPid()}.");

            $state = $localOrder->getState();
            $status = $localOrder->getStatus();

            $this->logger->debug("finalizePayment : can invoice ?", $localOrder->canInvoice());

            /** @var LocalInvoiceService $invoiceService */
            $invoiceService = $this->magento->get('Magento\Sales\Model\Service\InvoiceService');

            /** @var LocalInvoiceSender $invoiceSender */
            $invoiceSender = $this->magento->get('Magento\Sales\Model\Order\Email\Sender\InvoiceSender');

            /** @var LocalInvoice $invoice */
            $invoice = $invoiceService->prepareInvoice($localOrder);
            $invoice->register();
            $invoice->capture();
            $invoice->save();

            /** @var LocalTransaction $magentoTransaction */
            $magentoTransaction = $this->magento->create('Magento\Framework\DB\Transaction');

            $magentoTransaction
                ->addObject($invoice)
                ->addObject($invoice->getOrder())
                ->save()
            ;

            $invoiceSender->send($invoice);

            $localOrder
                ->setState($state)
                ->setStatus($status)
                ->save()
            ;

            $localOrder->addStatusHistoryComment("Notified customer about invoice #" . $invoice->getId())
                ->setIsCustomerNotified(true)
            ;

            $localOrder->save();
        }
    }
}