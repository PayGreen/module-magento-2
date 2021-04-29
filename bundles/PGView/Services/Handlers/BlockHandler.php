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
 * @version   2.0.1
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
     * @return string[]
     * @throws Exception
     */
    public function buildBlocks($target)
    {
        $blocks = array();
        $unpositionnedBlocks = array();

        foreach ($this->config as $config) {
            if ($config['requirements']) {
                if (!$this->requirementHandler->areFulfilled($config['requirements'])) {
                    continue;
                }
            }

            $isEnabled = in_array($config['enabled'], array(null, true), true);

            if ($isEnabled && ($config['target'] === $target)) {
                $data = $config['data'] ? $config['data'] : array();
                
                if ($config['position'] && ($config['position'] > 0)) {
                    $position = $config['position'];
                } else {
                    $position = 'unpositionned';
                }

                if ($config['view']) {
                    $blocks[$position] = $this->viewHandler->renderView($config['view'], $data);
                } elseif ($config['template']) {
                    $data['content'] = $this->viewHandler->renderTemplate($config['template'], $data);
                    $blocks[$position] = $this->viewHandler->renderTemplate('blocks/layout', $data);
                } elseif ($config['action']) {
                    $blocks[$position] = $this->buildActionBlock($config['action'], $data);
                } else {
                    throw new Exception("Block configuration must contain 'view' or 'template' key.");
                }
            }

            if (array_key_exists('unpositionned', $blocks)) {
                $unpositionnedBlocks[] = $blocks['unpositionned'];
                unset($blocks['unpositionned']);
            }
            ksort($blocks, SORT_NUMERIC);
        }
        
        $blocks = array_merge($blocks, $unpositionnedBlocks);

        return $blocks;
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
        return $this->viewHandler->renderTemplate('blocks/layout', $data);
    }
}
