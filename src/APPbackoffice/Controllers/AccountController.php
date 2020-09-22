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
 * @version   1.1.1
 */

class APPbackofficeControllersAccountController extends APPbackofficeFoundationsAbstractBackofficeController
{
    private static $ACCOUNT_SETTINGS = array(
        'private_key',
        'public_key'
    );

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function saveAccountConfigurationAction()
    {
        /** @var PGDomainServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        /** @var PGServerInterfacesActionInterface $action */
        $action = $this->delegate('settings.save', array(
            'form_name' => 'authentication',
            'redirection' => $this->getLinker()->buildBackOfficeUrl('backoffice.account.display')
        ));

        $response = $action->process();

        if ($action->isSuccess()) {
            $paygreenFacade->resetApiFacade();
        }

        return $response;
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws PGClientExceptionsPaymentRequestException
     * @throws Exception
     */
    public function activateAccountAction()
    {
        /** @var PGClientServicesApiFacade $apiFacade */
        $apiFacade = $this->getService('paygreen.facade')->getApiFacade();

        /** @var PGFrameworkServicesHandlersCacheHandler $cacheHandler */
        $cacheHandler = $this->getService('handler.cache');

        $activate = (bool) $this->getRequest()->get('activation');

        /** @var PGClientEntitiesResponse $apiResponse */
        $apiResponse = $apiFacade->activateShop($activate);

        if ($apiResponse->isSuccess()) {
            $cacheHandler->clearCache();

            $this->success('account.actions.toggle.result.success');
        } else {
            $this->failure('account.actions.toggle.result.failure');
        }

        return $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.account.display'));
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws Exception
     */
    public function disconnectAction()
    {
        /** @var PGFrameworkServicesSettings $settings */
        $settings = $this->getService('settings');

        $settings->remove('private_key');
        $settings->remove('public_key');

        $this->success('backoffice.actions.credentials.reset.result.success');

        return $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.account.display'));
    }

    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws PGClientExceptionsPaymentException
     * @throws PGClientExceptionsPaymentRequestException
     * @throws PGDomainExceptionsPaygreenAccountException
     * @throws Exception
     */
    public function displayAccountHeaderAction()
    {
        /** @var PGDomainServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        $infoShop = '';
        $infoAccount = '';

        if ($paygreenFacade->isConnected()) {
            /** @var stdClass $infoShop */
            $infoShop = $paygreenFacade->getStatusShop();
            $infoAccount = $paygreenFacade->getAccountInfos();
        }

        return $this->buildTemplateResponse('page-account')
            ->addData('activationFormView', $this->buildActivationFormView($infoAccount))
            ->addData('settingsFormView', $this->buildSettingsFormView('authentication', 'backoffice.account.save'))
            ->addData('connected', $paygreenFacade->isConnected())
            ->addData('infoShop', $infoShop)
            ->addData('infoAccount', $infoAccount)
        ;
    }

    /**
     * @param $infoAccount
     * @return PGViewComponentsBox
     * @throws Exception
     */
    protected function buildActivationFormView($infoAccount)
    {
        $action = $this->getLinker()->buildBackOfficeUrl('backoffice.account.activation');

        $values = array(
            'activation' => ($infoAccount ? $infoAccount->activate : false)
        );

        $view = $this->buildForm('account_activation', $values)
            ->buildView()
            ->setAction($action)
        ;

        return new PGViewComponentsBox($view);
    }
}
