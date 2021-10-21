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

namespace PGI\Module\BOCharity\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGCharity\Services\Handlers\CharityAuthenticationHandler;
use PGI\Module\PGForm\Interfaces\FormInterface;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGView\Components\Box as BoxComponent;
use Exception;

/**
 * Class AccountController
 * @package BOCharity\Services\Controllers
 */
class AccountController extends AbstractBackofficeController
{
    /** @var CharityAuthenticationHandler */
    private $charityAuthenticationHandler;

    public function setCharityAuthenticationHandler(CharityAuthenticationHandler $charityAuthenticationHandler)
    {
        $this->charityAuthenticationHandler = $charityAuthenticationHandler;
    }

    /**
     * @return BoxComponent
     * @throws Exception
     */
    protected function buildAuthenticationFormView()
    {
        /** @var Settings $settings */
        $settings = $this->getSettings();

        $action = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.charity_account.save');

        $values = array(
            'client_id' => $settings->get('charity_client_id')
        );

        $view = $this->buildForm('charity_authentication', $values)
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
        $this->charityAuthenticationHandler->disconnect();

        $this->success('actions.charity_authentication.reset.result.success');

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.charity_account.display'));
    }

    /**
     * @throws Exception
     */
    public function saveCharityAccountConfigurationAction()
    {
        /** @var FormInterface $form */
        $form = $this->buildForm('charity_authentication', $this->getRequest()->getAll());
        $result = null;

        if ($form->isValid()) {
            $isConnected = $this->charityAuthenticationHandler->connect(
                $form->getValue('client_id'),
                $form->getValue('login'),
                $form->getValue('password')
            );

            if ($isConnected) {
                $this->success('actions.charity_authentication.save.result.success');
                $result = $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.charity_account.display'));
            } else {
                $this->failure('actions.charity_authentication.save.result.failure');
            }
        } else {
            $this->failure('actions.charity_authentication.save.result.invalid');
        }

        if ($result === null) {
            $result = $this->forward('charity_account.display', array(
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

        if ($this->charityAuthenticationHandler->isConnected()) {
            $client_id = $settings->get('charity_client_id');
            $username = $settings->get('charity_client_username');
            $infos = array(
                'blocks.charity_account_infos.form.client_id' => $client_id,
                'blocks.charity_account_infos.form.username' => $username
            );
        }

        return $this->buildTemplateResponse('charity_account/block-infos')
            ->addData('infos', $infos)
        ;
    }

    /**
     * @throws Exception
     */
    public function displayAccountLoginAction()
    {
        return $this->buildTemplateResponse('charity_account/block-login')
            ->addData(
                'form',
                $this->buildAuthenticationFormView()
            )
        ;
    }
}
