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
 * @version   2.2.0
 *
 */

namespace PGI\Module\BOPayment\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGForm\Interfaces\FormInterface;
use PGI\Module\PGForm\Interfaces\Views\FormViewInterface;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGPayment\Services\Managers\CategoryHasPaymentTypeManager;
use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGShop\Services\Managers\CategoryManager;
use PGI\Module\PGView\Components\Box as BoxComponent;
use Exception;

/**
 * Class EligibleAmountsController
 * @package BOPayment\Services\Controllers
 */
class EligibleAmountsController extends AbstractBackofficeController
{
    /** @var CategoryHasPaymentTypeManager */
    private $categoryPaymentManager;

    /** @var CategoryManager */
    private $categoryManager;

    public function __construct(
        CategoryHasPaymentTypeManager $categoryPaymentManager,
        CategoryManager $categoryManager
    ) {
        $this->categoryPaymentManager = $categoryPaymentManager;
        $this->categoryManager = $categoryManager;
    }

    /**
     * @return RedirectionResponseComponent
     * @throws Exception
     */
    public function saveCategoryPaymentsAction()
    {
        /** @var FormInterface $form */
        $form = $this->buildForm('eligible_amounts', $this->getRequest()->getAll());

        if ($form->isValid()) {
            $this->categoryPaymentManager->saveCategoryPayments($form['eligible_amounts']);

            $this->success('actions.eligible_amounts.save.result.success');
        } else {
            $this->failure('actions.eligible_amounts.save.result.failure');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.eligible_amounts.display'));
    }

    /**
     * @return RedirectionResponseComponent
     * @throws Exception
     */
    public function saveShippingPaymentsAction()
    {
        /** @var Settings $settings */
        $settings = $this->getSettings();

        /** @var FormInterface $form */
        $form = $this->buildForm('exclusion_shipping_cost', $this->getRequest()->getAll());

        if ($form->isValid()) {
            $settings->set('shipping_deactivated_payment_modes', $form['payment_types']);

            $this->success('actions.exclusion_shipping_cost.save.result.success');
        } else {
            $this->failure('actions.exclusion_shipping_cost.save.result.failure');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.eligible_amounts.display'));
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayFormEligibleAmountsAction()
    {
        $response =  $this->buildTemplateResponse('eligible-amounts/block-form-eligible-amounts', array(
            'eligibleAmountsViewForm' => $this->buildEligibleAmountsForm()
        ));

        return $response;
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayFormExclusionShippingCostsAction()
    {
        $response =  $this->buildTemplateResponse('eligible-amounts/block-form-exclusion-shipping-costs', array(
            'shippingCostViewForm' => $this->buildShippingCostForm()
        ));

        return $response;
    }

    /**
     * @return BoxComponent
     * @throws Exception
     */
    private function buildEligibleAmountsForm()
    {
        /** @var FormViewInterface $eligibleAmountsViewForm */
        $eligibleAmountsViewForm = $this->buildForm('eligible_amounts')
            ->setValue('eligible_amounts', $this->categoryManager->getRawCategories())
            ->buildView();

        $eligibleAmountsViewForm->setAction(
            $this->getLinkHandler()->buildBackOfficeUrl('backoffice.eligible_amounts.categories.save')
        );

        return new BoxComponent($eligibleAmountsViewForm);
    }

    /**
     * @return BoxComponent
     * @throws Exception
     */
    private function buildShippingCostForm()
    {

        /** @var Settings $settings */
        $settings = $this->getSettings();

        /** @var FormViewInterface $shippingCostViewForm */
        $shippingCostViewForm = $this->buildForm('exclusion_shipping_cost')
            ->setValue('payment_types', $settings->get('shipping_deactivated_payment_modes'))
            ->buildView();

        $shippingCostViewForm->setAction(
            $this->getLinkHandler()->buildBackOfficeUrl('backoffice.eligible_amounts.shipping.save')
        );

        return new BoxComponent($shippingCostViewForm);
    }
}
