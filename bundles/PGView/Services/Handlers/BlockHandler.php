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

namespace PGI\Module\PGView\Services\Handlers;

use PGI\Module\PGFramework\Services\Handlers\RequirementHandler;
use PGI\Module\PGModule\Components\Output as OutputComponent;
use PGI\Module\PGServer\Components\Requests\Internal as InternalRequestComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGServer\Foundations\AbstractResponse;
use PGI\Module\PGServer\Services\Dispatcher;
use PGI\Module\PGSystem\Components\Bag as BagComponent;
use PGI\Module\PGView\Services\Handlers\ViewHandler;
use Exception;

/**
 * Class BlockHandler
 * @package PGView\Services\Handlers
 */
class BlockHandler
{
    /** @var ViewHandler */
    private $viewHandler;

    /** @var RequirementHandler */
    private $requirementHandler;

    /** @var Dispatcher */
    private $dispatcher;

    /** @var BagComponent[] */
    private $config = array();

    public function __construct(
        ViewHandler $viewHandler,
        RequirementHandler $requirementHandler,
        Dispatcher $dispatcher,
        array $config
    ) {
        $this->viewHandler = $viewHandler;
        $this->requirementHandler = $requirementHandler;
        $this->dispatcher = $dispatcher;

        foreach ($config as $name => $block) {
            $this->config[$name] = new BagComponent($block);
        }
    }

    /**
     * @param string $target
     * @return OutputComponent
     * @throws Exception
     */
    public function getBlocks($target)
    {
        $aggregatedBlocks = new OutputComponent();

        foreach ($this->buildBlocks($target) as $block) {
            $aggregatedBlocks->merge($block);
        }

        return $aggregatedBlocks;
    }

    /**
     * @param string $target
     * @return OutputComponent[]
     * @throws Exception
     */
    private function buildBlocks($target)
    {
        $fixedBlocks = array();
        $floatingBlocks = array();

        foreach ($this->config as $config) {
            if ($config['requirements']) {
                if (!$this->requirementHandler->areFulfilled($config['requirements'])) {
                    continue;
                }
            }

            $isEnabled = in_array($config['enabled'], array(null, true), true);

            if ($isEnabled && ($config['target'] === $target)) {
                $data = $config['data'] ? $config['data'] : array();

                $block = $this->buildBlock($config, $data);

                if ($config['position'] && ($config['position'] > 0)) {
                    $position = $config['position'];
                    $fixedBlocks[$position] = $block;
                } else {
                    $floatingBlocks[] = $block;
                }
            }
        }

        ksort($fixedBlocks, SORT_NUMERIC);

        return array_merge($fixedBlocks, $floatingBlocks);
    }

    /**
     * @param BagComponent $config
     * @param array $data
     * @return OutputComponent
     * @throws Exception
     */
    private function buildBlock(BagComponent $config, array $data)
    {
        if ($config['view']) {
            $html = $this->viewHandler->renderView($config['view'], $data);
            $block = new OutputComponent($html);
        } elseif ($config['template']) {
            $data['content'] = $this->viewHandler->renderTemplate($config['template'], $data);
            $html = $this->viewHandler->renderTemplate('blocks/layout', $data);
            $block = new OutputComponent($html);
        } elseif ($config['action']) {
            $block = $this->buildActionBlock($config['action'], $data);
        } else {
            throw new Exception("Block configuration must contain 'action', 'view' or 'template' key.");
        }

        return $block;
    }

    private function buildActionBlock($action, array $data)
    {
        $request = new InternalRequestComponent($data);

        /** @var AbstractResponse $response */
        $response = $this->dispatcher->dispatch($request, $action);

        if (!$response instanceof TemplateResponseComponent) {
            throw new Exception("Block handler only support TemplateResponses.");
        }

        $data['content'] = $this->viewHandler->renderTemplate($response->getTemplatePath(), $response->getData());

        $html = $this->viewHandler->renderTemplate('blocks/layout', $data);

        $output = new OutputComponent($html);

        $output->addResources($response->getResources());

        return $output;
    }
}
