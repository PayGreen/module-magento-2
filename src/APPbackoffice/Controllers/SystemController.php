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

class APPbackofficeControllersSystemController extends APPbackofficeFoundationsAbstractBackofficeController
{
    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayDataAction()
    {
        /** @var PGModuleServicesModuleFacade $moduleFacade */
        $moduleFacade = $this->getService('facade.module');

        /** @var PGDomainServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        if (function_exists('curl_version')) {
            $curl_data = curl_version();
        } else {
            $curl_data = array(
                'version' => 'NA',
                'ssl_version' => 'NA'
            );
        }

        return $this->buildTemplateResponse('page-system')
            ->addData('platforme', $moduleFacade->getApplicationName())
            ->addData('version_platforme', $moduleFacade->getApplicationVersion())
            ->addData('version_php', PHP_VERSION)
            ->addData('version_module', PAYGREEN_MODULE_VERSION)
            ->addData('version_framework', $paygreenFacade::VERSION)
            ->addData('version_curl', $curl_data['version'])
            ->addData('version_ssl', $curl_data['ssl_version'])
        ;
    }
}
