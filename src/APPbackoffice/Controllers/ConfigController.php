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
 * @version   1.2.3
 *
 */

class APPbackofficeControllersConfigController extends APPbackofficeFoundationsAbstractBackofficeController
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function saveModuleConfigurationAction()
    {
        return $this
            ->delegate('settings.save', array(
                'form_name' => 'config',
                'redirection' => $this->getLinker()->buildBackOfficeUrl('backoffice.config.display')
            ))
            ->process()
        ;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function saveModuleCustomizationAction()
    {
        return $this
            ->delegate('settings.save', array(
                'form_name' => 'settings_customization',
                'redirection' => $this->getLinker()->buildBackOfficeUrl('backoffice.config.display')
            ))
            ->process()
        ;
    }

    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayConfigFormAction()
    {
        return $this->buildTemplateResponse('page-admin-config');
    }
}
