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
 * Class PGPaymentServicesHandlersPaymentCreationHandler
 * @package PGPayment\Services\Handlers
 */
class PGPaymentServicesHandlersPaymentCreationHandler extends PGSystemFoundationsObject
{
    /** @var PGSystemComponentsBag */
    private $config;

    public function __construct(array $config)
    {
        $this->config = new PGSystemComponentsBag($config);
    }

    public function getTarget($name)
    {
        return $this->config["targets.$name"];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function buildCustomerEntrypointURL()
    {
        /** @var PGServerServicesHandlersLink $linkHandler */
        $linkHandler = $this->getService('handler.link');

        $customerEntrypoint = $this->config['entrypoints.customer'];

        return $linkHandler->buildFrontOfficeUrl($customerEntrypoint);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function buildIPNEntrypointURL()
    {
        /** @var PGServerServicesHandlersLink $linkHandler */
        $linkHandler = $this->getService('handler.link');

        $ipnEntrypoint = $this->config['entrypoints.ipn'];

        return $linkHandler->buildFrontOfficeUrl($ipnEntrypoint);
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @return string
     * @throws APIPaymentExceptionsPayment
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    public function buildPayment(PGPaymentInterfacesEntitiesButtonInterface $button)
    {
        /** @var PGShopInterfacesProvisionersPrePayment $prePaymentProvisioner */
        $prePaymentProvisioner = $this->getService('provisioner.pre_payment');

        /** @var APIPaymentComponentsResponse $response */
        $response = $this->createPayment($prePaymentProvisioner, $button);

        if (!$response->isSuccess()) {
            throw new Exception("Unable to create payment data.");
        }

        return $response->data->url;
    }

    /**
     * @param PGShopInterfacesProvisionersPrePayment $prePaymentProvisioner
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @param array $urls
     * @return APIPaymentComponentsResponse
     * @throws APIPaymentExceptionsPayment
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    public function createPayment(
        PGShopInterfacesProvisionersPrePayment $prePaymentProvisioner,
        PGPaymentInterfacesEntitiesButtonInterface $button
    ) {
        /** @var PGPaymentServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        /** @var PGPaymentServicesResponsabilityChainsPaymentCreation $paymentCreationResponsabilityChain */
        $paymentCreationResponsabilityChain = $this->getService('responsability_chain.payment_creation');

        $data = $paymentCreationResponsabilityChain->buildPaymentCreationData($button, $prePaymentProvisioner);

        /** @var APIPaymentComponentsResponse|null $response */
        $response = null;

        switch ($button->getPaymentMode()) {
            case PGPaymentData::MODE_CASH:
                $response = $paygreenFacade->getApiFacade()->createCash($data);
                break;

            case PGPaymentData::MODE_RECURRING:
                $response = $paygreenFacade->getApiFacade()->createSubscription($data);
                break;

            case PGPaymentData::MODE_XTIME:
                $response = $paygreenFacade->getApiFacade()->createXTime($data);
                break;

            case PGPaymentData::MODE_TOKENIZE:
                $response = $paygreenFacade->getApiFacade()->createTokenize($data);
                break;

            default:
                $message = "Unknown payment mode: '{$button->getPaymentMode()}'.";
                throw new APIPaymentExceptionsPayment($message);
        }

        return $response;
    }
}
