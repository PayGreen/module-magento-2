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
 * @version   2.5.2
 *
 */

namespace PGI\Module\BOPayment\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGFramework\Services\Handlers\CacheHandler;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use PGI\Module\PGPayment\Services\Managers\TransactionManager;
use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use Exception;

/**
 * Class PluginController
 * @package BOPayment\Services\Controllers
 */
class PluginController extends AbstractBackofficeController
{
    /** @var PaygreenFacade */
    private $paygreenFacade;

    /** @var CacheHandler */
    private $cacheHandler;

    /** @var TransactionManager */
    private $transactionManager;

    public function __construct(
        PaygreenFacade $paygreenFacade,
        CacheHandler $cacheHandler,
        TransactionManager $transactionManager
    ) {
        $this->paygreenFacade = $paygreenFacade;
        $this->cacheHandler = $cacheHandler;
        $this->transactionManager = $transactionManager;
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayAction()
    {
        /** @var Settings $settings */
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
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayProductsAction()
    {
        /** @var Settings $settings */
        $settings = $this->getSettings();

        $isPaymentActivated = $settings->get('payment_kit_activation');

        return $this->buildTemplateResponse('payment/block-payment-products')
            ->addData('paymentActivated', $isPaymentActivated)
            ;
    }

    /**
     * @return RedirectionResponseComponent
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
     * @return RedirectionResponseComponent
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
            'count' => $this->transactionManager->getCountOfTheLastHours(),
            'amount' => $this->transactionManager->getAmountOfTheLastHours()
        );

        $data[] = array(
            'period' => 'week',
            'count' => $this->transactionManager->getCountOfTheLastSevenDays(),
            'amount' => $this->transactionManager->getAmountOfTheLastSevenDays()
        );

        $data[] = array(
            'period' => 'month',
            'count' => $this->transactionManager->getCountOfTheLastThirtyDays(),
            'amount' => $this->transactionManager->getAmountOfTheLastThirtyDays()
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
