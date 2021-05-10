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
 * Class BOTreeControllersPlugin
 * @package BOTree\Controllers
 */
class BOTreeControllersPlugin extends BOModuleFoundationsAbstractBackofficeController
{
    /** @var BOTreeServicesHandlersAuthentication */
    private $treeAuthenticationHandler;

    public function setTreeAuthenticationHandler(PGTreeServicesHandlersTreeAuthentication $treeAuthenticationHandler)
    {
        $this->treeAuthenticationHandler = $treeAuthenticationHandler;
    }
    
    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayAction()
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = $this->getSettings();

        $client_id = '';
        $isConnected = false;

        $isTreeActivated = $settings->get('tree_active');

        if ($isTreeActivated) {
            if ($this->treeAuthenticationHandler->isConnected()) {
                $isConnected = true;
                $client_id = $settings->get('tree_client_id');
            }
        }

        return $this->buildTemplateResponse('tree/block-tree')
            ->addData('treeActivationFormView', $this->buildSettingsFormView('tree_activation', 'backoffice.tree.save'))
            ->addData('connected', $isConnected)
            ->addData('treeActivated', $isTreeActivated)
            ->addData('clientId', $client_id)
        ;
    }
}
