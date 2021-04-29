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
 * @version   2.0.1
 *
 */

/**
 * Class PGMagentoPaymentServicesListenersDisplayPaymentSuccessMessage
 * @package PGMagentoPayment\Services\Listeners
 */
class PGMagentoPaymentServicesListenersDisplayPaymentSuccessMessage
{
    /** @var PGIntlServicesHandlersTranslationHandler */
    private $translationHandler;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(
        PGIntlServicesHandlersTranslationHandler $translationHandler,
        PGModuleServicesLogger $logger
    ) {
        $this->translationHandler = $translationHandler;
        $this->logger = $logger;
    }

    /**
     * @param PGModuleComponentsEventsOutput $event
     * @throws Exception
     */
    public function display(PGModuleComponentsEventsOutput $event)
    {
        if ($this->isPaygreenPayment($event)) {
            $message = $this->translationHandler->translate('message_payment_success');
            $event->getOutput()->addContent("<p>$message</p>");
        } else {
            $this->logger->debug("Not a paygreen payment 'message_payment_success' not display.");
        }
    }

    /**
     * @param PGModuleComponentsEventsOutput $event
     * @return bool
     * @throws Exception
     */
    private function isPaygreenPayment($event)
    {
        return ($event->getData('paymentMethod') === 'paygreenpayment');
    }
}