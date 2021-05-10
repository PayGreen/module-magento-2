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
 * Class BOPaymentControllersAccount
 * @package BOPayment\Controllers
 */
class BOPaymentControllersAccount extends BOModuleFoundationsAbstractBackofficeController
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
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    public function activateAccountAction()
    {
        /** @var APIPaymentServicesApiFacade $apiFacade */
        $apiFacade = $this->paygreenFacade->getApiFacade();

        $activate = (bool) $this->getRequest()->get('activation');

        /** @var APIPaymentComponentsResponse $apiResponse */
        $apiResponse = $apiFacade->activateShop($activate);

        if ($apiResponse->isSuccess()) {
            $this->cacheHandler->clearCache();

            $this->success('actions.account_activation.toggle.result.success');
        } else {
            $this->failure('actions.account_activation.toggle.result.failure');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.account.display'));
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws Exception
     */
    public function disconnectAction()
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = $this->getSettings();

        $settings->remove('private_key');
        $settings->remove('public_key');

        $this->success('actions.authentication.reset.result.success');

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.account.display'));
    }

    /**
     * @param $infoAccount
     * @return PGViewComponentsBox
     * @throws Exception
     */
    protected function buildActivationFormView($infoAccount)
    {
        $action = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.account.activation');

        $values = array(
            'activation' => ($infoAccount ? $infoAccount->activate : false)
        );

        $view = $this->buildForm('account_activation', $values)
            ->buildView()
            ->setAction($action)
        ;

        return new PGViewComponentsBox($view);
    }

    public function displayAccountStatusAction()
    {
        $infoAccount = '';

        if ($this->paygreenFacade->isConnected()) {
            $infoAccount = $this->paygreenFacade->getAccountInfos();
        }

        return $this->buildTemplateResponse('account/block-status')
            ->addData('form', $this->buildActivationFormView($infoAccount))
        ;
    }

    public function displayAccountInfosAction()
    {
        $infoAccount = '';

        $infos = null;

        if ($this->paygreenFacade->isConnected()) {
            $infoAccount = $this->paygreenFacade->getAccountInfos();
            $infos = array(
                'blocks.account_infos.form.url' => $infoAccount->url,
                'blocks.account_infos.form.siret' => $infoAccount->siret,
                'blocks.account_infos.form.iban' => $infoAccount->IBAN
            );
        }

        return $this->buildTemplateResponse('account/block-infos')
            ->addData('infos', $infos)
        ;
    }
}
