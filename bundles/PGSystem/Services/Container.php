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
 * Class PGSystemServicesContainer
 * @package PGSystem\Services
 */
class PGSystemServicesContainer
{
    private $services = array();

    /** @var PGSystemComponentsParameters  */
    private $parameters;

    /** @var PGSystemComponentsServiceLibrary */
    private $library;

    /** @var PGSystemComponentsServiceBuilder */
    private $builder;

    /** @var PGSystemServicesContainer|null  */
    private static $instance = null;

    /**
     * PGSystemServicesContainer constructor.
     */
    private function __construct()
    {
        $this->library = new PGSystemComponentsServiceLibrary();
        $this->parameters = new PGSystemComponentsParameters();

        $parser = new PGSystemComponentsParser($this->parameters);

        $this->builder = new PGSystemComponentsServiceBuilder($this, $this->library, $parser);

        $parser->setBuilder($this->builder);

        $this->services = array(
            'container' => $this,
            'parameters' => $this->parameters,
            'parser' => $parser,
            'service.library' => $this->library,
            'service.builder' => $this->builder
        );
    }

    /**
     * @return PGSystemServicesContainer
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function reset()
    {
        $this->library->reset();
        $this->parameters->reset();

        $static_services = array();

        foreach ($this->services as $name => $service) {
            if ($this->library->isStatic($name)) {
                $static_services[$name] = $service;
            }
        }

        $this->services = $static_services;
    }

    /**
     * @param string $name
     * @return object
     * @throws Exception
     */
    public function get($name)
    {
        if (array_key_exists($name, $this->services)) {
            return $this->services[$name];
        } else {
            return $this->builder->build($name);
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->services);
    }

    /**
     * @param string $name
     * @param object $service
     * @return $this
     */
    public function set($name, $service)
    {
        if (!isset($this->library[$name])) {
            throw new LogicException("Attempt to set a non-defined service : '$name'.");
        }

        $this->services[$name] = $service;

        return $this;
    }
}
