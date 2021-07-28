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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGView\Services\Handlers;

use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use PGI\Module\PGSystem\Services\Pathfinder;
use PGI\Module\PGView\Interfaces\ViewInterface;
use PGI\Module\PGView\Services\Handlers\SmartyHandler;
use Exception;

/**
 * Class ViewHandler
 * @package PGView\Services\Handlers
 */
class ViewHandler
{
    /** @var AggregatorComponent */
    private $viewAggregator;

    /** @var SmartyHandler */
    private $smartyHandler;

    /** @var Pathfinder */
    private $pathfinder;

    public function __construct(
        AggregatorComponent $viewAggregator,
        SmartyHandler $smartyHandler,
        Pathfinder $pathfinder
    ) {
        $this->viewAggregator = $viewAggregator;
        $this->smartyHandler = $smartyHandler;
        $this->pathfinder = $pathfinder;
    }

    /**
     * @param string $name
     * @return ViewInterface
     * @throws Exception
     */
    public function buildView($name)
    {
        return $this->viewAggregator->getService($name);
    }

    /**
     * @param string $name
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function renderView($name, array $data = array())
    {
        return $this->buildView($name)
            ->setData($data)
            ->render()
        ;
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function renderTemplate($template, array $data = array())
    {
        return $this->smartyHandler->compileTemplate(
            $template . '.tpl',
            $data
        );
    }
}
