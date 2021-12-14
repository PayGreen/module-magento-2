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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGServer\Services\Renderers\Processors;

use PGI\Module\PGFramework\Services\Handlers\OutputHandler;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGServer\Components\Responses\Text as TextResponseComponent;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use PGI\Module\PGView\Services\Handlers\ViewHandler;
use Exception;

/**
 * Class OutputTemplateRendererProcessor
 * @package PGServer\Services\Renderers\Processors
 */
class OutputTemplateRendererProcessor extends AbstractObject
{
    /** @var ViewHandler */
    private $viewHandler;

    /** @var OutputHandler */
    private $outputHandler;

    public function __construct(
        ViewHandler $viewHandler,
        OutputHandler $outputHandler
    ) {
        $this->viewHandler = $viewHandler;
        $this->outputHandler = $outputHandler;
    }

    /**
     * @param TemplateResponseComponent $response
     * @return TextResponseComponent
     * @throws Exception
     */
    public function process(TemplateResponseComponent $response)
    {
        $this->outputHandler->addResources($response->getResources());

        $content = $this->viewHandler->renderTemplate(
            $response->getTemplatePath(),
            $response->getData()
        );

        $this->outputHandler->setContent($content);

        return null;
    }
}
