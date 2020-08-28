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

class APPbackofficeControllersSystemController extends APPbackofficeFoundationsAbstractBackofficeController
{
    /**
     * @return PGServerFoundationsAbstractResponse
     * @throws Exception
     */
    public function saveSupportConfigurationAction()
    {
        return $this
            ->delegate('settings.save', array(
                'form_name' => 'settings_support',
                'redirection' => $this->getLinker()->buildBackOfficeUrl('backoffice.system.display')
            ))
            ->process()
        ;
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws Exception
     */
    public function deleteLogFileAction()
    {
        /** @var PGFrameworkServicesPathfinder $pathfinder */
        $pathfinder = $this->getService('pathfinder');

        $files = $this->getFileList();

        $file = $this->getRequest()->get('filename');
        $filename = $pathfinder->toAbsolutePath('log', "/$file");

        if (in_array($file, $files) && file_exists($filename)) {
            unlink($filename);
            $this->success('system.logs.actions.delete.success');
        } elseif (!in_array($file, $files)) {
            $this->failure('system.logs.errors.invalid_file');
        } elseif (!file_exists($filename)) {
            $this->failure('system.logs.errors.file_not_found');
        }

        return $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.system.display'));
    }

    /**
     * @return PGServerComponentsResponsesFileResponse
     * @throws Exception
     */
    public function downloadLogFileAction()
    {
        /** @var PGFrameworkServicesPathfinder $pathfinder */
        $pathfinder = $this->getService('pathfinder');

        $files = $this->getFileList();

        $file = $this->getRequest()->get('filename');
        $filename = $pathfinder->toAbsolutePath('log', "/$file");

        /** @var PGServerComponentsResponsesFileResponse $response */
        $response = new PGServerComponentsResponsesFileResponse($this->getRequest());

        if (in_array($file, $files) && file_exists($filename)) {
            $response->setPath($filename);
        } elseif (!in_array($file, $files)) {
            $this->failure('system.logs.errors.invalid_file');
        } elseif (!file_exists($filename)) {
            $this->failure('system.logs.errors.file_not_found');
        }

        return $response;
    }

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
            ->addData('supportFormView', $this->buildSettingsFormView('settings_support', 'backoffice.system.save_support_config'))
            ->addData('platforme', $moduleFacade->getApplicationName())
            ->addData('version_platforme', $moduleFacade->getApplicationVersion())
            ->addData('version_php', PHP_VERSION)
            ->addData('version_module', PAYGREEN_MODULE_VERSION)
            ->addData('version_framework', $paygreenFacade::VERSION)
            ->addData('version_curl', $curl_data['version'])
            ->addData('version_ssl', $curl_data['ssl_version'])
            ->addData('log_data', $this->getLogData())
        ;
    }

    private function getFileList()
    {
        $files = array('module.log', 'api.log');

        if (PAYGREEN_ENV === 'DEV') {
            $files[] = 'error.log';
        }

        return $files;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getLogData()
    {
        $files = $this->getFileList();

        $logs = array();

        /** @var PGFrameworkServicesPathfinder $pathfinder */
        $pathfinder = $this->getService('pathfinder');

        foreach ($files as $file) {
            $filename = $pathfinder->toAbsolutePath('log', "/$file");
            $datetime = new DateTime();

            if (is_file($filename)) {
                $logs[] = array(
                    'name' => $file,
                    'size' => $this->getHumanFilesize(filesize($filename)),
                    'updatedAt' => $datetime->setTimestamp(filemtime($filename))->format('d M Y H:i:s'),
                    'action' => true
                );
            } else {
                $logs[] = array(
                    'name' => $file,
                    'size' => "Log inexistant",
                    'updatedAt' => "NA",
                    'action' => false
                );
            }
        }


        return $logs;
    }

    private function getHumanFilesize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
}
