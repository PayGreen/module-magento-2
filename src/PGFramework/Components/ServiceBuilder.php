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

class PGFrameworkComponentsServiceBuilder
{
    /** @var PGFrameworkContainer */
    private $container;

    /** @var PGFrameworkComponentsServiceLibrary */
    private $library;

    /** @var PGFrameworkComponentsParser */
    private $parser;

    /** @var PGFrameworkComponentsServiceCallDelayer|null */
    private $callDelayer = null;

    private $underConstructionServices = array();

    private $processing = false;

    /**
     * PGFrameworkComponentsServiceBuilder constructor.
     * @param PGFrameworkContainer $container
     * @param PGFrameworkComponentsServiceLibrary $library
     */
    public function __construct(PGFrameworkContainer $container, PGFrameworkComponentsServiceLibrary $library, PGFrameworkComponentsParser $parser)
    {
        $this->container = $container;
        $this->library = $library;
        $this->parser = $parser;
    }

    /**
     * @param string $name
     * @return object
     * @throws ReflectionException
     * @throws Exception
     */
    public function build($name)
    {
        if ($this->processing) {
            return $this->buildService($name);
        } else {
            return $this->buildServiceChain($name);
        }
    }

    /**
     * @param string $name
     * @return object
     * @throws Exception
     */
    protected function buildServiceChain($name)
    {
        $this->processing = true;

        $this->callDelayer = new PGFrameworkComponentsServiceCallDelayer($this->container, $this->parser);

        try {
            $service = $this->buildService($name);

            $this->callDelayer->callDelayed();

            $this->cleaning();

            return $service;
        } catch (Exception $exception) {
            if ($this->container->has('logger')) {
                /** @var PGFrameworkServicesLogger $logger */
                $logger = $this->container->get('logger');

                $logger->emergency("Error during building the service '$name'.", $exception);
            }

            $this->cleaning();

            throw $exception;
        }
    }

    protected function cleaning()
    {
        $this->callDelayer = null;
        $this->processing = false;
    }

    /**
     * @param string $name
     * @return object
     * @throws Exception
     * @throws ReflectionException
     */
    protected function buildService($name)
    {
        if ($this->container->has($name)) {
            return $this->container->get($name);
        } elseif (!isset($this->library[$name])) {
            throw new LogicException("Call to a non-existant service : '$name'.");
        } elseif (in_array($name, $this->underConstructionServices)) {
            throw new LogicException("Circular reference detected for service : '$name'.");
        } elseif ($this->library->isAbstract($name)) {
            throw new LogicException("Unable to implements abstract service : '$name'.");
        }

        try {
            $this->underConstructionServices[] = $name;

            $definition = $this->library[$name];

            if (!array_key_exists('class', $definition)) {
                throw new LogicException("Target service definition has no class name : '$name'.");
            }

            $class = $definition['class'];
            $arguments = array();

            if (array_key_exists('arguments', $definition)) {
                if (!is_array($definition['arguments'])) {
                    $message = "Target service definition has inconsistent argument list : '$name'.";
                    throw new LogicException($message);
                }

                foreach ($definition['arguments'] as $argument) {
                    $arguments[] = $this->parser->parseAll($argument);
                }
            }

            $reflexionClass = new ReflectionClass($class);

            $service = $reflexionClass->newInstanceArgs($arguments);

            if (array_key_exists('calls', $definition)) {
                $subject = $this->library->isShared($name) ? null : $service;
                $this->callDelayer->addCalls($name, $definition['calls'], $subject);
            }

            if (array_key_exists('catch', $definition)) {
                $this->collectTaggedServices($service, $name, $definition);
            }

            if ($this->library->isShared($name)) {
                $this->container->set($name, $service);
            }

            $this->finalizeServiceConstruction($name);
        } catch (Exception $exception) {
            $this->finalizeServiceConstruction($name);
            throw $exception;
        }

        return $service;
    }

    protected function finalizeServiceConstruction($name)
    {
        $index = array_search($name, $this->underConstructionServices);
        unset($this->underConstructionServices[$index]);
    }

    /**
     * @param object $service
     * @param string $name
     * @param array $definition
     */
    protected function collectTaggedServices($service, $name, array $definition)
    {
        $catch = $definition['catch'];

        if (!is_array($catch)) {
            $message = "Target service definition has inconsistent catch options : '$name'.";
            throw new LogicException($message);
        } elseif (!array_key_exists('tag', $catch)) {
            $message = "Target service definition has catch option without 'tag' parameter : '$name'.";
            throw new LogicException($message);
        } elseif (!array_key_exists('method', $catch)) {
            $message = "Target service definition has catch option without 'method' parameter : '$name'.";
            throw new LogicException($message);
        }

        $built = array_key_exists('built', $catch) && ($catch['built'] === true);

        $findedTags = $this->library->getTaggedServices($catch['tag']);

        foreach ($findedTags as $findedTag) {
            $argument = $built ? '@' . $findedTag['name'] : $findedTag['name'];
            $arguments = array_merge(array($argument), $findedTag['options']);

            $call = array(
                'method' => $catch['method'],
                'arguments' => $arguments
            );

            $subject = $this->library->isShared($name) ? null : $service;

            $this->callDelayer->addCall($name, $call, $subject);
        }
    }
}
