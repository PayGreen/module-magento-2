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
 * @version   1.2.2
 *
 */

/**
 * Class PGFrameworkContainer
 * @package PGFramework
 */
class PGFrameworkContainer
{
    private $services = array();

    /** @var PGFrameworkComponentsParameters  */
    private $parameters;

    /** @var PGFrameworkComponentsServiceLibrary */
    private $library;

    /** @var PGFrameworkComponentsServiceBuilder */
    private $builder;

    /** @var PGFrameworkContainer|null  */
    private static $instance = null;

    /**
     * PGFrameworkContainer constructor.
     */
    private function __construct()
    {
        $this->library = new PGFrameworkComponentsServiceLibrary();
        $this->parameters = new PGFrameworkComponentsParameters();

        $parser = new PGFrameworkComponentsParser($this->parameters);

        $this->builder = new PGFrameworkComponentsServiceBuilder($this, $this->library, $parser);

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
     * @return PGFrameworkContainer
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function reset(array $additionalServices = array())
    {
        $defaultServices = array(
            'autoloader' => $this->get('autoloader'),
            'pathfinder' => $this->get('pathfinder'),
            'bootstrap' => $this->get('bootstrap'),
            'container' => $this,
            'parameters' => $this->parameters,
            'parser' => $this->get('parser'),
            'service.library' => $this->library,
            'service.builder' => $this->builder
        );

        $services = array_merge(
            $defaultServices,
            $additionalServices
        );

        $this->services = array();

        $this->library->reset();
        $this->parameters->reset();

        foreach ($services as $name => $service) {
            $this->set($name, $service);
        }
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
        } elseif ($this->library->isAbstract($name)) {
            throw new LogicException("Unable to set abstract service : '$name'.");
        }

        $this->services[$name] = $service;

        return $this;
    }
}
