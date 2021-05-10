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
 * Class BOPaymentControllersPlugin
 * @package BOPayment\Controllers
 */
class BOPaymentControllersPlugin extends BOModuleFoundationsAbstractBackofficeController
{
    /** @var PGPaymentServicesPaygreenFacade */
    private $paygreenFacade;

    /** @var PGFrameworkServicesHandlersCacheHandler */
    private $cacheHandler;

    public function __construct(
        PGPaymentServicesPaygreenFacade $paygreenFacade,
        PGFrameworkServicesHandlersCacheHandler $cacheHandler
    ) {
        $this->paygreenFacade = $paygreenFacade;
        $this->cacheHandler = $cacheHandler;
    }

    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayAction()
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = $this->getSettings();

        $isPaymentActivated = $settings->get('active');

        $infoAccount = '';

        if ($this->paygreenFacade->isConnected()) {
            $infoAccount = $this->paygreenFacade->getAccountInfos();
        }

        return $this->buildTemplateResponse('payment/block-payment')
            ->addData('paymentActivationFormView', $this->buildPaymentActivationFormView())
            ->addData('connected', $this->paygreenFacade->isConnected())
            ->addData('paymentActivated', $isPaymentActivated)
            ->addData('infoAccount', $infoAccount)
        ;
    }

    /**
     * @return PGViewComponentsBox
     * @throws Exception
     */
    protected function buildPaymentActivationFormView()
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = $this->getSettings();

        $isActive = $settings->get('active');

        $action = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.payment.activation');

        $values = array(
            'active' => $isActive
        );

        $view = $this->buildForm('payment_activation', $values)
            ->buildView()
            ->setAction($action)
        ;

        return new PGViewComponentsBox($view);
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    public function activatePaymentAction()
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = $this->getSettings();

        $activate = (bool) $this->getRequest()->get('active');

        $settings->set('active', $activate);

        if ($activate === $settings->get('active')) {
            $this->cacheHandler->clearCache();

            $this->success('actions.payment_activation.toggle.result.success');
        } else {
            $this->failure('actions.payment_activation.toggle.result.failure');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.home.display'));
    }
}
