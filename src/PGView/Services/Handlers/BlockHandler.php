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
 * @version   1.1.1
 */

/**
 * Class PGViewServicesHandlersBlockHandler
 * @package PGView\Services\Handlers
 */
class PGViewServicesHandlersBlockHandler
{
    /** @var PGViewServicesHandlersViewHandler */
    private $viewHandler;

    /** @var PGFrameworkComponentsBag[] */
    private $config = array();

    public function __construct(
        PGViewServicesHandlersViewHandler $viewHandler,
        array $config
    ) {
        $this->viewHandler = $viewHandler;

        foreach ($config as $name => $block) {
            $this->config[$name] = new PGFrameworkComponentsBag($block);
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

        foreach ($this->config as $config) {
            $isEnabled = in_array($config['enabled'], array(null, true), true);

            if ($isEnabled && ($config['target'] === $target)) {
                $data = $config['data'] ? $config['data'] : array();
                if ($config['view']) {
                    $blocks[] = $this->viewHandler->renderView($config['view'], $data);
                } elseif ($config['template']) {
                    $data['content'] = $this->viewHandler->renderTemplate($config['template'], $data);
                    $blocks[] = $this->viewHandler->renderTemplate('blocks/layout', $data);
                } else {
                    throw new Exception("Block configuration must contain 'view' or 'template' key.");
                }
            }
        }

        return $blocks;
    }
}
