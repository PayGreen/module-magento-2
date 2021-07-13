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
 * @version   2.1.1
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

    /** @var PGPaymentServicesManagersTransactionManager */
    private $transactionManager;

    public function __construct(
        PGPaymentServicesPaygreenFacade $paygreenFacade,
        PGFrameworkServicesHandlersCacheHandler $cacheHandler,
        PGPaymentServicesManagersTransactionManager $transactionManager
    ) {
        $this->paygreenFacade = $paygreenFacade;
        $this->cacheHandler = $cacheHandler;
        $this->transactionManager = $transactionManager;
    }

    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayAction()
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = $this->getSettings();

        $isPaymentActivated = $settings->get('payment_activation');

        $infos = array();

        if ($this->paygreenFacade->isConnected()) {
            $infos['public_key'] = $settings->get('public_key');
            $infos['payments_overview'] = $this->getPaymentOverviewData();
        }

        return $this->buildTemplateResponse('payment/block-payment')
            ->addData('connected', $this->paygreenFacade->isConnected())
            ->addData('paymentActivated', $isPaymentActivated)
            ->addData('paymentKitInfos', $infos)
            ->addData('growth', $this->transactionManager->getGrowthOfTheMonth())
        ;
    }

    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayProductsAction()
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = $this->getSettings();

        $isPaymentActivated = $settings->get('payment_kit_activation');

        return $this->buildTemplateResponse('payment/block-payment-products')
            ->addData('paymentActivated', $isPaymentActivated)
            ;
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws Exception
     */
    public function paymentActivationAction()
    {
        $settings = $this->getSettings();

        $paymentActivation = $settings->get('payment_activation');

        $settings->set('payment_activation', !$paymentActivation);

        if ($paymentActivation) {
            $this->success('actions.payment_activation.toggle.result.success.disabled');
        } else {
            $this->success('actions.payment_activation.toggle.result.success.enabled');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.home.display'));
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws Exception
     */
    public function paymentProductsActivationAction()
    {
        $settings = $this->getSettings();

        $paymentActivation = $settings->get('payment_kit_activation');

        $settings->set('payment_kit_activation', !$paymentActivation);

        if ($paymentActivation) {
            $this->success('actions.payment_activation.toggle.result.success.disabled');
        } else {
            $this->success('actions.payment_activation.toggle.result.success.enabled');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.products.display'));
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getPaymentOverviewData()
    {
        $data = array();

        $data[] = array(
            'period' => 'day',
            'count' => $this->transactionManager->getCountOfTheDay(),
            'amount' => $this->transactionManager->getAmountOfTheDay()
        );

        $data[] = array(
            'period' => 'week',
            'count' => $this->transactionManager->getCountOfTheWeek(),
            'amount' => $this->transactionManager->getAmountOfTheWeek()
        );

        $data[] = array(
            'period' => 'month',
            'count' => $this->transactionManager->getCountOfTheMonth(),
            'amount' => $this->transactionManager->getAmountOfTheMonth()
        );

        foreach ($data as $index => $value) {
            $data[$index]['amount'] = $this->formatAmount($value['amount']);
        }

        return $data;
    }

    /**
     * @param $amount
     * @return string
     */
    private function formatAmount($amount)
    {
        return number_format($amount, 2, '.', ' ');
    }
}
