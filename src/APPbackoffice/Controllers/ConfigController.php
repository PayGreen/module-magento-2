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
 * @version   1.0.1
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
        return $this->buildTemplateResponse('page-admin-config', array(
            'configFormView' => $this->buildSettingsFormView('config', 'backoffice.config.save'),
            'customizationFormView' => $this->buildSettingsFormView('settings_customization', 'backoffice.config.save_customization')
        ));
    }
}
