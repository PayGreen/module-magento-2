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
 * Class BOModuleControllersLogsController
 * @package BOModule\Controllers
 */
class BOModuleControllersLogsController extends BOModuleFoundationsAbstractBackofficeController
{
    /** @var PGSystemServicesPathfinder */
    private $pathfinder;

    public function __construct(PGSystemServicesPathfinder $pathfinder)
    {
        $this->pathfinder = $pathfinder;
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws Exception
     */
    public function deleteLogFileAction()
    {
        $files = $this->getFileList();

        $file = $this->getRequest()->get('filename');
        $filename = $this->pathfinder->toAbsolutePath('log', "/$file");

        if (in_array($file, $files) && file_exists($filename)) {
            unlink($filename);
            $this->success('actions.logs.delete.result.success');
        } elseif (!in_array($file, $files)) {
            $this->failure('actions.logs.default.errors.invalid_file');
        } elseif (!file_exists($filename)) {
            $this->failure('actions.logs.default.errors.file_not_found');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.support.display'));
    }

    /**
     * @return PGServerComponentsResponsesFileResponse
     * @throws Exception
     */
    public function downloadLogFileAction()
    {
        $files = $this->getFileList();

        $file = $this->getRequest()->get('filename');
        $filename = $this->pathfinder->toAbsolutePath('log', "/$file");

        /** @var PGServerComponentsResponsesFileResponse $response */
        $response = new PGServerComponentsResponsesFileResponse($this->getRequest());

        if (in_array($file, $files) && file_exists($filename)) {
            $response->setPath($filename);
        } elseif (!in_array($file, $files)) {
            $this->failure('actions.logs.default.errors.invalid_file');
        } elseif (!file_exists($filename)) {
            $this->failure('actions.logs.default.errors.file_not_found');
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
