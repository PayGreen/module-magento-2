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
 * @version   2.4.0
 *
 */

namespace PGI\Module\BOTree\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGForm\Interfaces\FormInterface;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGTree\Services\Handlers\TreeAuthenticationHandler;
use PGI\Module\PGView\Components\Box as BoxComponent;
use Exception;

/**
 * Class AccountController
 * @package BOTree\Services\Controllers
 */
class AccountController extends AbstractBackofficeController
{
    /** @var TreeAuthenticationHandler */
    private $treeAuthenticationHandler;

    public function setTreeAuthenticationHandler(TreeAuthenticationHandler $treeAuthenticationHandler)
    {
        $this->treeAuthenticationHandler = $treeAuthenticationHandler;
    }

    /**
     * @return BoxComponent
     * @throws Exception
     */
    protected function buildAuthenticationFormView()
    {
        /** @var Settings $settings */
        $settings = $this->getSettings();

        $action = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.tree_account.save');

        $values = array(
            'client_id' => $settings->get('tree_client_id')
        );

        $view = $this->buildForm('tree_authentication', $values)
            ->buildView()
            ->setAction($action)
        ;

        return new BoxComponent($view);
    }

    /**
     * @return RedirectionResponseComponent
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
        /** @var FormInterface $form */
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
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayAccountInfosAction()
    {
        /** @var Settings $settings */
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


        return $this->buildTemplateResponse('tree_account/block-infos')
            ->addData('infos', $infos)
        ;
    }

    public function displayAccountLoginAction()
    {
        return $this->buildTemplateResponse('tree_account/block-login')
            ->addData('form', $this->buildAuthenticationFormView('tree_authentication', 'backoffice.tree_account.save'))
        ;
    }
}
