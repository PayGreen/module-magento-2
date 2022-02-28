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
 * @version   2.6.0
 *
 */

namespace PGI\Module\BOPayment\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use PGI\Module\PGServer\Components\Resources\StyleFile as StyleFileResourceComponent;
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

    public function __construct(
        PaygreenFacade $paygreenFacade
    ) {
        $this->paygreenFacade = $paygreenFacade;
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

        return $this->buildTemplateResponse('payment/block-payment')
            ->addData('connected', $this->paygreenFacade->isConnected())
            ->addData('paymentActivated', $isPaymentActivated)
            ->addResource(new StyleFileResourceComponent('/css/payment-home-block.css'))

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
}
