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

namespace PGI\Module\PGPayment\Services\Handlers;

use PGI\Module\APIPayment\Components\Response as ResponseComponent;
use PGI\Module\APIPayment\Exceptions\Payment as PaymentException;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGPayment\Data;
use PGI\Module\PGPayment\Interfaces\Entities\ButtonEntityInterface;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use PGI\Module\PGPayment\Services\ResponsabilityChains\PaymentCreationResponsabilityChain;
use PGI\Module\PGServer\Services\Handlers\LinkHandler;
use PGI\Module\PGShop\Interfaces\Provisioners\PrePaymentProvisionerInterface;
use PGI\Module\PGSystem\Components\Bag as BagComponent;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class PaymentCreationHandler
 * @package PGPayment\Services\Handlers
 */
class PaymentCreationHandler extends AbstractObject
{
    /** @var BagComponent */
    private $config;

    public function __construct(array $config)
    {
        $this->config = new BagComponent($config);
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
        /** @var LinkHandler $linkHandler */
        $linkHandler = $this->getService('handler.link');

        $customerEntrypoint = $this->config['entrypoints.customer'];

        var_dump($customerEntrypoint);
        die();

        return $linkHandler->buildFrontOfficeUrl($customerEntrypoint);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function buildIPNEntrypointURL()
    {
        /** @var LinkHandler $linkHandler */
        $linkHandler = $this->getService('handler.link');

        $ipnEntrypoint = $this->config['entrypoints.ipn'];

        return $linkHandler->buildFrontOfficeUrl($ipnEntrypoint);
    }

    /**
     * @param ButtonEntityInterface $button
     * @return string
     * @throws PaymentException
     * @throws ResponseException
     * @throws Exception
     */
    public function buildPayment(ButtonEntityInterface $button)
    {
        /** @var PrePaymentProvisionerInterface $prePaymentProvisioner */
        $prePaymentProvisioner = $this->getService('provisioner.pre_payment');

        /** @var ResponseComponent $response */
        $response = $this->createPayment($prePaymentProvisioner, $button);

        if (!$response->isSuccess()) {
            throw new Exception("Unable to create payment data.");
        }

        return $response->data->url;
    }

    /**
     * @param PrePaymentProvisionerInterface $prePaymentProvisioner
     * @param ButtonEntityInterface $button
     * @param array $urls
     * @return ResponseComponent
     * @throws PaymentException
     * @throws ResponseException
     * @throws Exception
     */
    public function createPayment(
        PrePaymentProvisionerInterface $prePaymentProvisioner,
        ButtonEntityInterface $button
    ) {
        /** @var PaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        /** @var PaymentCreationResponsabilityChain $paymentCreationResponsabilityChain */
        $paymentCreationResponsabilityChain = $this->getService('responsability_chain.payment_creation');

        $data = $paymentCreationResponsabilityChain->buildPaymentCreationData($button, $prePaymentProvisioner);

        /** @var ResponseComponent|null $response */
        $response = null;

        switch ($button->getPaymentMode()) {
            case Data::MODE_CASH:
                $response = $paygreenFacade->getApiFacade()->createCash($data);
                break;

            case Data::MODE_RECURRING:
                $response = $paygreenFacade->getApiFacade()->createSubscription($data);
                break;

            case Data::MODE_XTIME:
                $response = $paygreenFacade->getApiFacade()->createXTime($data);
                break;

            case Data::MODE_TOKENIZE:
                $response = $paygreenFacade->getApiFacade()->createTokenize($data);
                break;

            default:
                $message = "Unknown payment mode: '{$button->getPaymentMode()}'.";
                throw new PaymentException($message);
        }

        return $response;
    }
}
