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
 * Class FOPaymentControllersPayment
 * @package FOPayment\Controllers
 */
class FOPaymentControllersPayment extends PGServerFoundationsAbstractController
{
    /** @var PGPaymentServicesPaygreenFacade */
    private $paygreenFacade;

    /** @var PGPaymentServicesHandlersPaymentCreationHandler */
    protected $paymentCreationHandler;

    /** @var PGPaymentServicesProcessorsPaymentValidationProcessor */
    private $processor;

    /** @var PGPaymentServicesManagersButtonManager */
    private $buttonManager;

    /** @var PGPaymentServicesManagersPaymentTypeManager */
    private $paymentTypeManager;

    public function __construct(
        PGPaymentServicesPaygreenFacade $paygreenFacade,
        PGPaymentServicesHandlersPaymentCreationHandler $paymentCreationHandler,
        PGPaymentServicesProcessorsPaymentValidationProcessor $processor,
        PGPaymentServicesManagersButtonManager $buttonManager,
        PGPaymentServicesManagersPaymentTypeManager $paymentTypeManager
    ) {
        $this->paygreenFacade = $paygreenFacade;
        $this->paymentCreationHandler = $paymentCreationHandler;
        $this->processor = $processor;
        $this->buttonManager = $buttonManager;
        $this->paymentTypeManager = $paymentTypeManager;
    }

    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    public function validatePaymentAction()
    {
        /** @var PGServerFoundationsAbstractResponse $response */
        $response = null;

        try {
            /** @var PGPaymentInterfacesEntitiesButtonInterface $button */
            $button = $this->retrieveButtonFromRequest();

            $details = "in mode {$button->getPaymentMode()} and type {$button->getPaymentType()}";
            $this->getLogger()->info(
                "Begin payment with button #{$button->id()} $details."
            );

            $url = $this->paymentCreationHandler->buildPayment($button);
            $this->getLogger()->debug("Payment URL generated : " . $url);

            $insite = (($button->getIntegration() === 'INSITE') && $this->paygreenFacade->verifyInsiteValidity());

            if ($insite) {
                $response = $this->buildIFramePaymentResponse($button, $url);
                $this->getLogger()->notice("Display insite payment form.");
            } else {
                $response = $this->redirect($url);
                $this->getLogger()->notice("Redirect to PayGreen payment form.");
            }
        } catch (Exception $exception) {
            $this->getLogger()->critical("Validation payment error : " . $exception->getMessage(), $exception);

            $response = $this->forward('displayNotification@front.notification', array(
                'title' => 'frontoffice.payment.errors.creation.title',
                'message' => 'frontoffice.payment.errors.creation.message',
                'url' => array(
                        'link' => $this->getLinkHandler()->buildLocalUrl('checkout'),
                        'text' => 'frontoffice.payment.errors.creation.link',
                        'reload' => false
                    ),
                'exceptions' => array($exception)
            ));
        }

        return $response;
    }

    /**
     * @return PGServerComponentsResponsesHTTPResponse
     * @throws Exception
     */
    public function receiveAction()
    {
        $response = new PGServerComponentsResponsesHTTPResponse($this->getRequest());

        try {
            $pid = $this->getRequest()->get('pid');

            $this->getLogger()->info("Receive IPN for PID : '$pid'.");

            $task = new PGPaymentComponentsTasksPaymentValidation($pid);

            $this->processor->execute($task);

            switch ($task->getStatus()) {
                case PGPaymentComponentsTasksPaymentValidation::STATE_SUCCESS:
                case PGPaymentComponentsTasksPaymentValidation::STATE_PAYMENT_REFUSED:
                case PGPaymentComponentsTasksPaymentValidation::STATE_PAYMENT_ABORTED:
                    $response->setStatus(200);
                    break;

                case PGPaymentComponentsTasksPaymentValidation::STATE_PID_NOT_FOUND:
                case PGPaymentComponentsTasksPaymentValidation::STATE_PID_LOCKED:
                case PGPaymentComponentsTasksPaymentValidation::STATE_INCONSISTENT_CONTEXT:
                case PGPaymentComponentsTasksPaymentValidation::STATE_FATAL_ERROR:
                case PGPaymentComponentsTasksPaymentValidation::STATE_WORKFLOW_ERROR:
                case PGPaymentComponentsTasksPaymentValidation::STATE_PAYGREEN_UNAVAILABLE:
                default:
                    $statusName = $task->getStatusName($task->getStatus());
                    $this->getLogger()->error("Notification failure. Final state : '$statusName'.'");
                    $response->setStatus(500);
            }
        } catch (Exception $exception) {
            $this->getLogger()->critical("Notification exception : " . $exception->getMessage(), $exception);
            $response->setStatus(500);
        }

        return $response;
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @param $url
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws APIPaymentExceptionsPayment
     * @throws PGPaymentExceptionsPaygreenAccountException
     * @throws Exception
     */
    protected function buildIFramePaymentResponse(PGPaymentInterfacesEntitiesButtonInterface $button, $url)
    {
        /** @var PGSystemComponentsParameters $parameters */
        $parameters = $this->getParameters();

        $this->getLogger()->debug("Build IFrame payment response for button #{$button->id()}.");

        $url = PGFrameworkToolsQuery::addParameters($url, array('display' => 'insite'));

        $iframeSize = $this->getIFrameSizes($button);

        $returnTarget = $parameters['payment.insite.return'];

        return $this->buildTemplateResponse('page-payment-iframe', array(
            'title' => $button->getLabel(),
            'id' => $button->id(),
            'url' => $url,
            'minWidthIframe' => $iframeSize['minWidth'],
            'minHeightIframe' => $iframeSize['minHeight'],
            'return_url' => $this->getLinkHandler()->buildUrl($returnTarget)
        ));
    }

    /**
     * @return PGPaymentInterfacesEntitiesButtonInterface
     * @throws Exception
     */
    protected function retrieveButtonFromRequest()
    {
        /** @var PGPaymentInterfacesEntitiesButtonInterface $button */
        $button = null;

        if ($this->getRequest()->has('button')) {
            $button = $this->getRequest()->get('button');
        } elseif ($this->getRequest()->has('id')) {
            $id_button = $this->getRequest()->get('id');

            $button = $this->buttonManager->getByPrimary($id_button);
        } else {
            throw new Exception("Payment actions require button parameter.");
        }

        if ($button === null) {
            throw new Exception("Payment button not found.");
        }

        return $button;
    }

    /**
     * @param PGPaymentInterfacesEntitiesButtonInterface $button
     * @return array
     * @throws APIPaymentExceptionsPayment
     * @throws PGClientExceptionsResponse
     * @throws PGPaymentExceptionsPaygreenAccountException
     */
    protected function getIFrameSizes(PGPaymentInterfacesEntitiesButtonInterface $button)
    {
        $shopInfo = $this->paygreenFacade->getAccountInfos();

        return $this->paymentTypeManager->getSizeIFrameFromPayment(
            isset($shopInfo->solidarityType) ? $shopInfo->solidarityType : null,
            $button->getPaymentType(),
            $button->getPaymentMode()
        );
    }
}
