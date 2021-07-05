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
 * @version   2.1.0
 *
 */

/**
 * Class PGViewServicesHandlersBlockHandler
 * @package PGView\Services\Handlers
 */
class PGViewServicesHandlersBlockHandler
{
    /** @var PGViewServicesHandlersViewHandler */
    private $viewHandler;

    /** @var PGFrameworkServicesHandlersRequirementHandler */
    private $requirementHandler;

    /** @var PGServerServicesDispatcher */
    private $dispatcher;

    /** @var PGSystemComponentsBag[] */
    private $config = array();

    public function __construct(
        PGViewServicesHandlersViewHandler $viewHandler,
        PGFrameworkServicesHandlersRequirementHandler $requirementHandler,
        PGServerServicesDispatcher $dispatcher,
        array $config
    ) {
        $this->viewHandler = $viewHandler;
        $this->requirementHandler = $requirementHandler;
        $this->dispatcher = $dispatcher;

        foreach ($config as $name => $block) {
            $this->config[$name] = new PGSystemComponentsBag($block);
        }
    }

    /**
     * @param string $target
     * @return PGModuleComponentsOutput
     * @throws Exception
     */
    public function getBlocks($target)
    {
        $aggregatedBlocks = new PGModuleComponentsOutput();

        foreach($this->buildBlocks($target) as $block) {
            $aggregatedBlocks->merge($block);
        }

        return $aggregatedBlocks;
    }

    /**
     * @param string $target
     * @return PGModuleComponentsOutput[]
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
     * @param PGSystemComponentsBag $config
     * @param array $data
     * @return PGModuleComponentsOutput
     * @throws Exception
     */
    private function buildBlock(PGSystemComponentsBag $config, array $data)
    {
        if ($config['view']) {
            $html = $this->viewHandler->renderView($config['view'], $data);
            $block = new PGModuleComponentsOutput($html);
        } elseif ($config['template']) {
            $data['content'] = $this->viewHandler->renderTemplate($config['template'], $data);
            $html = $this->viewHandler->renderTemplate('blocks/layout', $data);
            $block = new PGModuleComponentsOutput($html);
        } elseif ($config['action']) {
            $block = $this->buildActionBlock($config['action'], $data);
        } else {
            throw new Exception("Block configuration must contain 'action', 'view' or 'template' key.");
        }

        return $block;
    }

    private function buildActionBlock($action, array $data)
    {
        $request = new PGServerComponentsRequestsInternalRequest($data);

        /** @var PGServerFoundationsAbstractResponse $response */
        $response = $this->dispatcher->dispatch($request, $action);

        if (!$response instanceof PGServerComponentsResponsesTemplateResponse) {
            throw new Exception("Block handler only support TemplateResponses.");
        }

        $data['content'] = $this->viewHandler->renderTemplate($response->getTemplatePath(), $response->getData());

        $html = $this->viewHandler->renderTemplate('blocks/layout', $data);

        $output = new PGModuleComponentsOutput($html);

        $output->addResources($response->getResources());

        return $output;
    }
}
