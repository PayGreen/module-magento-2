<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.2.0
 */

class APPbackofficeControllersEligibleAmountsController extends APPbackofficeFoundationsAbstractBackofficeController
{
    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws Exception
     */
    public function saveCategoryPaymentsAction()
    {
        /** @var PGDomainServicesManagersCategoryHasPaymentTypeManager $categoryPaymentManager */
        $categoryPaymentManager = $this->getService('manager.category_has_payment_type');

        /** @var PGFormInterfacesFormInterface $form */
        $form = $this->buildForm('eligible_amounts', $this->getRequest()->getAll());

        if ($form->isValid()) {
            $categoryPaymentManager->saveCategoryPayments($form['eligible_amounts']);

            $this->success('eligible_amounts.actions.save_category_payments.result.success');
        } else {
            $this->failure('eligible_amounts.actions.save_category_payments.result.failure');
        }

        return $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.eligible_amounts.display'));
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws Exception
     */
    public function saveShippingPaymentsAction()
    {
        /** @var PGFrameworkServicesSettings $settings */
        $settings = $this->getService('settings');

        /** @var PGFormInterfacesFormInterface $form */
        $form = $this->buildForm('exclusion_shipping_cost', $this->getRequest()->getAll());

        if ($form->isValid()) {
            $settings->set('shipping_deactivated_payment_modes', $form['payment_types']);

            $this->success('eligible_amounts.actions.save_shipping_payments.result.success');
        } else {
            $this->failure('eligible_amounts.actions.save_shipping_payments.result.failure');
        }

        return $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.eligible_amounts.display'));
    }

    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayAction()
    {
        return $this->buildTemplateResponse('page-eligible-amounts', array(
            'eligibleAmountsViewForm' => $this->buildEligibleAmountsForm(),
            'shippingCostViewForm' => $this->buildShippingCostForm()
        ));
    }

    /**
     * @return PGViewComponentsBox
     * @throws Exception
     */
    private function buildEligibleAmountsForm()
    {
        /** @var PGDomainServicesManagersCategoryManager $categoryManager */
        $categoryManager = $this->getService('manager.category');

        /** @var PGFormInterfacesFormViewInterface $eligibleAmountsViewForm */
        $eligibleAmountsViewForm = $this->buildForm('eligible_amounts')
            ->setValue('eligible_amounts', $categoryManager->getRawCategories())
            ->buildView();

        $eligibleAmountsViewForm->setAction($this->getLinker()->buildBackOfficeUrl('backoffice.eligible_amounts.categories.save'));

        return new PGViewComponentsBox($eligibleAmountsViewForm);
    }

    /**
     * @return PGViewComponentsBox
     * @throws Exception
     */
    private function buildShippingCostForm()
    {

        /** @var PGFrameworkServicesSettings $settings */
        $settings = $this->getService('settings');

        /** @var PGFormInterfacesFormViewInterface $shippingCostViewForm */
        $shippingCostViewForm = $this->buildForm('exclusion_shipping_cost')
            ->setValue('payment_types', $settings->get('shipping_deactivated_payment_modes'))
            ->buildView();

        $shippingCostViewForm->setAction($this->getLinker()->buildBackOfficeUrl('backoffice.eligible_amounts.shipping.save'));

        return new PGViewComponentsBox($shippingCostViewForm);
    }
}
