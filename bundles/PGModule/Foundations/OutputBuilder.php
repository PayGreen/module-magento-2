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
 * Class PGModuleFoundationsEvent
 * @package PGModule\Foundations
 */
abstract class PGModuleFoundationsOutputBuilder implements PGModuleInterfacesBuildersOutput, PGSystemInterfacesServicesConfigurable
{
    /** @var PGSystemComponentsBag */
    private $config;

    /** @var PGViewServicesHandlersViewHandler */
    private $viewHandler;

    /**
     * PGModuleFoundationsOutputBuilder constructor.
     */
    public function __construct()
    {
        $this->setConfig(array());
    }

    /**
     * @inheritDoc
     */
    abstract public function build(array $data = array());

    /**
     * @param PGViewServicesHandlersViewHandler $viewHandler
     */
    public function setViewHandler(PGViewServicesHandlersViewHandler $viewHandler)
    {
        $this->viewHandler = $viewHandler;
    }

    /**
     * @return PGViewServicesHandlersViewHandler
     */
    protected function getViewHandler()
    {
        return $this->viewHandler;
    }

    /**
     * @inheritDoc
     */
    public function setConfig(array $config)
    {
        $this->config = new PGSystemComponentsBag($config);
    }

    /**
     * @inheritDoc
     */
    public function addConfig(array $config)
    {
        $this->config->merge($config);
    }

    /**
     * @inheritDoc
     */
    public function hasConfig($key)
    {
        return isset($this->config[$key]);
    }

    /**
     * @inheritDoc
     */
    public function getConfig($key)
    {
        return $this->config[$key];
    }
}
