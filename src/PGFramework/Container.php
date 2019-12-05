<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.4
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

    /**
     * @param string $name
     * @return object
     * @throws Exception
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->services)) {
            $this->builder->build($name);
        }

        return $this->services[$name];
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
