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
 * @version   0.3.2
 */

/**
 * Class PGDomainServicesHandlersPaymentCreationHandler
 * @package PGFramework\Services\Handlers
 */
class PGDomainServicesHandlersPaymentCreationHandler extends PGFrameworkFoundationsAbstractObject
{
    /**
     * @param PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @param array $urls
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentException
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function createPayment(
        PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner,
        PGDomainInterfacesEntitiesButtonInterface $button,
        array $urls
    ) {
        /** @var PGDomainServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        $data = $this->buildPaymentData($prePaymentProvisioner, $button, $urls);

        if (PAYGREEN_ENV === 'DEV') {
            /** @var PGDomainServicesHandlersTestingPaymentHandler $testingPaymentHandler */
            $testingPaymentHandler = $this->getService('handler.payment_testing');

            $testingPaymentHandler->savePaymentData($data);
        }

        /** @var PGClientEntitiesResponse|null $response */
        $response = null;

        switch ($button->getPaymentMode()) {
            case PGDomainData::MODE_CASH:
                $response = $paygreenFacade->getApiFacade()->createCash($data);
                break;

            case PGDomainData::MODE_RECURRING:
                $response = $paygreenFacade->getApiFacade()->createSubscription($data);
                break;

            case PGDomainData::MODE_XTIME:
                $response = $paygreenFacade->getApiFacade()->createXTime($data);
                break;

            case PGDomainData::MODE_TOKENIZE:
                $response = $paygreenFacade->getApiFacade()->createTokenize($data);
                break;

            default:
                $message = "Unknown payment mode : '{$button->getPaymentMode()}'.";
                throw new PGClientExceptionsPaymentException($message);
        }

        return $response;
    }

    protected function buildPaymentData(
        PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner,
        PGDomainInterfacesEntitiesButtonInterface $button,
        array $urls
    ) {
        $data = array();

        $data['orderId'] = $prePaymentProvisioner->getReference();
        $data['currency'] = $prePaymentProvisioner->getCurrency();
        $data['mode'] = $button->getPaymentMode();
        $data['paymentType'] = $button->getPaymentType();
        $data['metadata'] = $prePaymentProvisioner->getMetadata();

        $this->addCustomerData($data, $prePaymentProvisioner);

        $this->addEligibleAmountData($data, $prePaymentProvisioner, $button->getPaymentType());

        $this->addFingerPrintData($data, $prePaymentProvisioner);

        $data['returned_url'] = $urls['returned_url'];
        $data['notified_url'] = $urls['notified_url'];

        $data['amount'] = $prePaymentProvisioner->getTotalAmount();

        switch ($button->getPaymentMode()) {
            case PGDomainData::MODE_RECURRING:
                $data['orderDetails'] = array();
                $this->addRecurringData($data, $button, $prePaymentProvisioner->getTotalAmount());
                $this->addPaymentReportData($data, $button);
                break;

            case PGDomainData::MODE_XTIME:
                $data['orderDetails'] = array();
                $this->addXTimeData($data, $button, $prePaymentProvisioner->getTotalAmount());
                break;
        }

        return $data;
    }

    protected function addPaymentReportData(array &$data, PGDomainInterfacesEntitiesButtonInterface $button)
    {
        $paymentReport = $button->getPaymentReport();

        $paymentReportStartAt = ($paymentReport === 0) ? null : strtotime($paymentReport);

        $data['orderDetails'] += array(
            'day' => date('d'),
            'startAt' => $paymentReportStartAt
        );
    }

    protected function addXTimeData(array &$data, PGDomainInterfacesEntitiesButtonInterface $button, $amount)
    {
        $paymentNumber = $button->getPaymentNumber();

        if ($button->getFirstPaymentPart() > 0) {
            $firstAmount = ceil($amount / 100 * $button->getFirstPaymentPart());
        } else {
            $firstAmount = ceil($amount / $paymentNumber);
        }

        $data['orderDetails'] += array(
            'cycle' => PGDomainData::RECURRING_MONTHLY,
            'count' => $paymentNumber,
            'firstAmount' => $firstAmount
        );
    }

    protected function addRecurringData(array &$data, PGDomainInterfacesEntitiesButtonInterface $button, $amount)
    {
        $data['orderDetails'] += array(
            'cycle' => PGDomainData::RECURRING_MONTHLY,
            'count' => $button->getPaymentNumber(),
            'firstAmount' => $amount
        );
    }

    /**
     * @param array $data
     * @param PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner
     */
    protected function addCustomerData(
        array &$data,
        PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner
    ) {
        $id_user = $prePaymentProvisioner->getCustomerId();

        $data['buyer'] = array(
            'id' => $id_user ? $id_user : hash('md5', microtime()),
            'lastName' => substr($prePaymentProvisioner->getLastName(), 0, 50),
            'firstName' => substr($prePaymentProvisioner->getFirstName(), 0, 50),
            'email' => $prePaymentProvisioner->getMail(),
            'country' => $prePaymentProvisioner->getCountry()
        );
    }

    /**
     * @param array $data
     * @param PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner
     */
    protected function addFingerPrintData(
        array &$data,
        PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner
    ) {
        /** @var PGDomainServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        /** @var PGDomainServicesHandlersFingerprintHandler $fingerPrintHandler */
        $fingerPrintHandler = $this->getService('handler.fingerprint');

        try {
            $shopInfo = $paygreenFacade->getAccountInfos();

            if ($shopInfo->solidarityType === 'CCARBONE') {
                $carbon = $fingerPrintHandler->generateFingerprintDatas();

                if (!empty($carbon) && $carbon !== false) {
                    if (isset($carbon->data) && $carbon->data->idFingerprint) {
                        $data['idFingerprint'] = $carbon->data->idFingerprint;
                    }
                }
            }
        } catch (Exception $exception) {
            /** @var PGFrameworkServicesLogger $logger */
            $logger = $this->getService('logger');

            $logger->error("Unable to compute fingerprint : " . $exception->getMessage(), $exception);
        }
    }

    /**
     * Add eligible amount data.
     * @param array $data
     * @param PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner
     * @param string $type
     */
    protected function addEligibleAmountData(
        array &$data,
        PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner,
        $type
    ) {
        /** @var PGDomainServicesManagersProductManager $productManager */
        $productManager = $this->getService('manager.product');

        $eligible_amount = 0;

        /** @var PGDomainInterfacesEntitiesCartItemInterface $item */
        foreach ($prePaymentProvisioner->getItems() as $item) {
            if ($productManager->isEligibleProduct($item->getProduct(), $type)) {
                $eligible_amount += $item->getCost();
            }
        }

        if ($eligible_amount > 0) {
            if ($prePaymentProvisioner->getShippingAmount() > 0) {
                $eligible_amount += $this->getShippingEligibleAmount($prePaymentProvisioner, $type);
            }
        }

        $data['eligibleAmount'] = array(
            $type => $eligible_amount
        );
    }

    /**
     * @param PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner
     * @param string $type
     * @return int
     */
    protected function getShippingEligibleAmount(
        PGDomainInterfacesPrePaymentProvisionerInterface $prePaymentProvisioner,
        $type
    ) {
        /** @var PGFrameworkServicesSettings $settings */
        $settings = $this->getService('settings');

        $shippingDeactivatedPaymentTypes = $settings->get('shipping_deactivated_payment_modes');

        $isEligibleShipping = !in_array($type, $shippingDeactivatedPaymentTypes);

        return $isEligibleShipping ? $prePaymentProvisioner->getShippingAmount() : 0;
    }
}
