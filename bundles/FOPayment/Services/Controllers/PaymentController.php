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
 * @version   2.3.0
 *
 */

namespace PGI\Module\FOPayment\Services\Controllers;

use PGI\Module\APIPayment\Exceptions\Payment as PaymentException;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGFramework\Tools\Query as QueryTool;
use PGI\Module\PGModule\Services\Handlers\BehaviorHandler;
use PGI\Module\PGPayment\Components\Tasks\PaymentValidation as PaymentValidationTaskComponent;
use PGI\Module\PGPayment\Exceptions\PaygreenAccount as PaygreenAccountException;
use PGI\Module\PGPayment\Interfaces\Entities\ButtonEntityInterface;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use PGI\Module\PGPayment\Services\Handlers\PaymentCreationHandler;
use PGI\Module\PGPayment\Services\Managers\ButtonManager;
use PGI\Module\PGPayment\Services\Managers\PaymentTypeManager;
use PGI\Module\PGPayment\Services\Processors\PaymentValidationProcessor;
use PGI\Module\PGServer\Components\Responses\HTTP as HTTPResponseComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGServer\Foundations\AbstractController;
use PGI\Module\PGServer\Foundations\AbstractResponse;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;
use Exception;

/**
 * Class PaymentController
 * @package FOPayment\Services\Controllers
 */
class PaymentController extends AbstractController
{
    /** @var PaygreenFacade */
    private $paygreenFacade;

    /** @var PaymentCreationHandler */
    protected $paymentCreationHandler;

    /** @var PaymentValidationProcessor */
    private $processor;

    /** @var ButtonManager */
    private $buttonManager;

    /** @var PaymentTypeManager */
    private $paymentTypeManager;

    /** @var BehaviorHandler $behaviors */
    private $behaviors;

    public function __construct(
        PaygreenFacade $paygreenFacade,
        PaymentCreationHandler $paymentCreationHandler,
        PaymentValidationProcessor $processor,
        ButtonManager $buttonManager,
        PaymentTypeManager $paymentTypeManager,
        BehaviorHandler $behaviors
    ) {
        $this->paygreenFacade = $paygreenFacade;
        $this->paymentCreationHandler = $paymentCreationHandler;
        $this->processor = $processor;
        $this->buttonManager = $buttonManager;
        $this->paymentTypeManager = $paymentTypeManager;
        $this->behaviors = $behaviors;
    }

    /**
     * @return AbstractResponse
     * @throws Exception
     */
    public function validatePaymentAction()
    {
        /** @var AbstractResponse $response */
        $response = null;

        try {
            /** @var ButtonEntityInterface $button */
            $button = $this->retrieveButtonFromRequest();

            $details = "in mode {$button->getPaymentMode()} and type {$button->getPaymentType()}";
            $this->getLogger()->info(
                "Begin payment with button #{$button->id()} $details."
            );

            $url = $this->paymentCreationHandler->buildPayment($button);
            $this->getLogger()->debug("Payment URL generated : " . $url);

            $insite = (($this->behaviors->get('behavior_payment_insite')) && $this->paygreenFacade->verifyInsiteValidity());

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
     * @return HTTPResponseComponent
     * @throws Exception
     */
    public function receiveAction()
    {
        $response = new HTTPResponseComponent($this->getRequest());

        try {
            $pid = $this->getRequest()->get('pid');

            $this->getLogger()->info("Receive IPN for PID : '$pid'.");

            $task = new PaymentValidationTaskComponent($pid);

            $this->processor->execute($task);

            switch ($task->getStatus()) {
                case PaymentValidationTaskComponent::STATE_SUCCESS:
                case PaymentValidationTaskComponent::STATE_PAYMENT_REFUSED:
                case PaymentValidationTaskComponent::STATE_PAYMENT_ABORTED:
                    $response->setStatus(200);
                    break;

                case PaymentValidationTaskComponent::STATE_PID_NOT_FOUND:
                case PaymentValidationTaskComponent::STATE_PID_LOCKED:
                case PaymentValidationTaskComponent::STATE_INCONSISTENT_CONTEXT:
                case PaymentValidationTaskComponent::STATE_FATAL_ERROR:
                case PaymentValidationTaskComponent::STATE_WORKFLOW_ERROR:
                case PaymentValidationTaskComponent::STATE_PAYGREEN_UNAVAILABLE:
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
     * @param ButtonEntityInterface $button
     * @param $url
     * @return TemplateResponseComponent
     * @throws PaymentException
     * @throws PaygreenAccountException
     * @throws Exception
     */
    protected function buildIFramePaymentResponse(ButtonEntityInterface $button, $url)
    {
        /** @var ParametersComponent $parameters */
        $parameters = $this->getParameters();

        $this->getLogger()->debug("Build IFrame payment response for button #{$button->id()}.");

        $url = QueryTool::addParameters($url, array('display' => 'insite'));

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
     * @return ButtonEntityInterface
     * @throws Exception
     */
    protected function retrieveButtonFromRequest()
    {
        /** @var ButtonEntityInterface $button */
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
     * @param ButtonEntityInterface $button
     * @return array
     * @throws PaymentException
     * @throws ResponseException
     * @throws PaygreenAccountException
     */
    protected function getIFrameSizes(ButtonEntityInterface $button)
    {
        $shopInfo = $this->paygreenFacade->getAccountInfos();

        return $this->paymentTypeManager->getSizeIFrameFromPayment(
            isset($shopInfo->solidarityType) ? $shopInfo->solidarityType : null,
            $button->getPaymentType(),
            $button->getPaymentMode()
        );
    }
}
