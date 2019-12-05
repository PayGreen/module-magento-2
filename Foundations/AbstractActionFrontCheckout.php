<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.4
 */

namespace Paygreen\Payment\Foundations;

use Exception;
use PGClientEntitiesResponse;
use PGClientExceptionsPaymentException;
use PGClientExceptionsPaymentRequestException;
use PGDomainInterfacesEntitiesButtonInterface;
use PGDomainServicesHandlersPaymentCreationHandler;
use PGModuleProvisionersPrePaymentProvisioner;

abstract class AbstractActionFrontCheckout extends AbstractActionFront
{
    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return string
     * @throws PGClientExceptionsPaymentException
     * @throws PGClientExceptionsPaymentRequestException
     */
    protected function buildPayment(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        /** @var PGDomainServicesHandlersPaymentCreationHandler $paymentCreationHandler */
        $paymentCreationHandler = $this->getService('handler.payment_creation');

        /** @var PGModuleProvisionersPrePaymentProvisioner $prePaymentProvisioner */
        $prePaymentProvisioner = PGModuleProvisionersPrePaymentProvisioner::createFromSession();

        /** @var UrlInterface $urlBuilder */
        $urlBuilder = $this->getService('magento')->get('Magento\Framework\UrlInterface');

        /** @var PGClientEntitiesResponse $response */
        $response = $paymentCreationHandler->createPayment($prePaymentProvisioner, $button, array(
            'returned_url' => $urlBuilder->getUrl('pgfront/payment/validate'),
            'notified_url' => $urlBuilder->getUrl('pgfront/payment/notify')
        ));

        if (!$response->isSuccess()) {
            throw new Exception("Unable to create payment data.");
        }

        return $response->data->url;
    }

    /**
     * @param Exception $exception
     */
    protected function displayError(Exception $exception)
    {
        $this->getService('logger')->info("Paygreen\Payment\Controller\Checkout\Index::displayError");

        exit;
    }
}
