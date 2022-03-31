<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.1
 *
 */

namespace PGI\Module\BOModule\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGModule\Services\Handlers\DiagnosticHandler;
use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use Exception;

/**
 * Class DiagnosticController
 * @package BOModule\Services\Controllers
 */
class DiagnosticController extends AbstractBackofficeController
{
    /** @var DiagnosticHandler */
    private $diagnosticHandler;

    /**
     * @param DiagnosticHandler $diagnosticHandler
     */
    public function setDiagnosticHandler(DiagnosticHandler $diagnosticHandler)
    {
        $this->diagnosticHandler = $diagnosticHandler;
    }

    /**
     * @return RedirectionResponseComponent
     * @throws Exception
     */
    public function runAction()
    {
        $this->diagnosticHandler->run();

        $this->success('actions.diagnostic.correction.result.success');

        $url = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.support.display');

        return $this->redirect($url);
    }
}
