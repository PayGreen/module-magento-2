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
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGForm\Foundations;

use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use PGI\Module\PGView\Interfaces\ViewInterface;
use Exception;

/**
 * Class AbstractElement
 * @package PGForm\Foundations
 */
abstract class AbstractElement
{
    private $name;

    private $config = array();

    /** @var AggregatorComponent */
    private $viewAggregator;

    public function __construct($name, array $config = array())
    {
        $this->name = $name;
        $this->config = $config;
    }

    /**
     * @param AggregatorComponent $viewAggregator
     */
    public function setViewAggregator($viewAggregator)
    {
        $this->viewAggregator = $viewAggregator;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getConfig($key, $default = null)
    {
        return $this->hasConfig($key) ? $this->config[$key] : $default;
    }

    /**
     * @param string $key
     * @return self
     */
    protected function setConfig($key, $val)
    {
        $this->config[$key] = $val;

        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function hasConfig($key)
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * @return ViewInterface
     * @throws Exception
     */
    public function buildView()
    {
        $viewConfig = $this->getConfig('view', array());

        if (!array_key_exists('name', $viewConfig)) {
            throw new Exception("Unable to retrieve view name for current form element : '{$this->getName()}'.");
        }

        /** @var ViewInterface $view */
        $view = $this->viewAggregator->getService($viewConfig['name']);

        if (array_key_exists('data', $viewConfig)) {
            $view->setData($viewConfig['data']);
        }

        if (array_key_exists('template', $viewConfig)) {
            $view->setTemplate($viewConfig['template']);
        }

        return $view;
    }
}
