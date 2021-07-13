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
 * Class BOTreeControllersAccount
 * @package BOTree\Controllers
 */
class BOTreeControllersAccount extends BOModuleFoundationsAbstractBackofficeController
{
    /** @var PGTreeServicesHandlersTreeAuthentication */
    private $treeAuthenticationHandler;

    public function setTreeAuthenticationHandler(PGTreeServicesHandlersTreeAuthentication $treeAuthenticationHandler)
    {
        $this->treeAuthenticationHandler = $treeAuthenticationHandler;
    }

    /**
     * @return PGViewComponentsBox
     * @throws Exception
     */
    protected function buildAuthenticationFormView()
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = $this->getSettings();

        $action = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.tree_account.save');

        $values = array(
            'client_id' => $settings->get('tree_client_id')
        );

        $view = $this->buildForm('tree_authentication', $values)
            ->buildView()
            ->setAction($action)
        ;

        return new PGViewComponentsBox($view);
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws Exception
     */
    public function disconnectAction()
    {
        $this->treeAuthenticationHandler->disconnect();

        $this->success('actions.tree_authentication.reset.result.success');

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.tree_account.display'));
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function saveTreeAccountConfigurationAction()
    {
        /** @var PGFormInterfacesFormInterface $form */
        $form = $this->buildForm('tree_authentication', $this->getRequest()->getAll());
        $result = null;

        if ($form->isValid()) {

            $isConnected = $this->treeAuthenticationHandler->connect(
                $form->getValue('client_id'),
                $form->getValue('login'),
                $form->getValue('password')
            );

            if ($isConnected) {
                $this->success('actions.tree_authentication.save.result.success');
                $result = $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.tree_account.display'));
            } else {
                $this->failure('actions.tree_authentication.save.result.failure');
            }
        } else {
            $this->failure('actions.tree_authentication.save.result.invalid');
        }

        if ($result === null) {
            $result = $this->forward('tree_account.display', array(
                'form' => $form
            ));
        }

        return $result;
    }

    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayAccountInfosAction()
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = $this->getSettings();

        $client_id = '';

        $infos = null;

        if ($this->treeAuthenticationHandler->isConnected()) {
            $client_id = $settings->get('tree_client_id');
            $username = $settings->get('tree_client_username');
            $infos = array(
                'blocks.tree_account_infos.form.client_id' => $client_id,
                'blocks.tree_account_infos.form.username' => $username
            );
        }

        $serverLabel = 'data.tree_api_server.values.'.$this->getSettings()->get('tree_api_server');


        return $this->buildTemplateResponse('tree_account/block-infos')
            ->addData('infos', $infos)
            ->addData('tree_api_server', $serverLabel )
        ;
    }

    public function displayAccountLoginAction()
    {
        return $this->buildTemplateResponse('tree_account/block-login')
            ->addData('form', $this->buildAuthenticationFormView('tree_authentication', 'backoffice.tree_account.save'))
        ;
    }
}
