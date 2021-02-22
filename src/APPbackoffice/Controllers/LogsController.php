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
 * @version   1.2.5
 *
 */

class APPbackofficeControllersLogsController extends APPbackofficeFoundationsAbstractBackofficeController
{
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
            $this->success('blocks.logs.actions.delete.success');
        } elseif (!in_array($file, $files)) {
            $this->failure('system.logs.errors.invalid_file');
        } elseif (!file_exists($filename)) {
            $this->failure('system.logs.errors.file_not_found');
        }

        return $this->redirect($this->getLinker()->buildBackOfficeUrl('backoffice.support.display'));
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

    private function getFileList()
    {
        $files = array('module.log', 'api.log', 'view.log');

        if (PAYGREEN_ENV === 'DEV') {
            $files[] = 'error.log';
        }

        return $files;
    }
}
