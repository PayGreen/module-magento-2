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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGSystem\Components\Service;

use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGSystem\Components\Parser as ParserComponent;
use PGI\Module\PGSystem\Components\Service\CallDelayer as CallDelayerServiceComponent;
use PGI\Module\PGSystem\Components\Service\Library as LibraryServiceComponent;
use PGI\Module\PGSystem\Exceptions\ParserConstant as ParserConstantException;
use PGI\Module\PGSystem\Exceptions\ParserParameter as ParserParameterException;
use PGI\Module\PGSystem\Interfaces\Services\ConfigurableServiceInterface;
use PGI\Module\PGSystem\Services\Container;
use Exception;
use LogicException;
use ReflectionException;
use ReflectionClass;

/**
 * Class Builder
 * @package PGSystem\Components\Service
 */
class Builder
{
    /** @var Container */
    private $container;

    /** @var LibraryServiceComponent */
    private $library;

    /** @var ParserComponent */
    private $parser;

    /** @var CallDelayerServiceComponent|null */
    private $callDelayer = null;

    private $underConstructionServices = array();

    private $processing = false;

    /**
     * Builder constructor.
     * @param Container $container
     * @param LibraryServiceComponent $library
     */
    public function __construct(
        Container $container,
        LibraryServiceComponent $library,
        ParserComponent $parser
    ) {
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

        $this->callDelayer = new CallDelayerServiceComponent($this->container, $this->parser);

        try {
            $service = $this->buildService($name);

            $this->callDelayer->callDelayed();

            $this->cleaning();

            return $service;
        } catch (Exception $exception) {
            if ($this->container->has('logger')) {
                /** @var Logger $logger */
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
     * @throws LogicException
     * @throws Exception
     */
    protected function buildService($name)
    {
        if ($this->container->has($name)) {
            return $this->container->get($name);
        } elseif (!isset($this->library[$name])) {
            throw new LogicException("Call to a non-existant service : '$name'.");
        } elseif (in_array($name, $this->underConstructionServices)) {
            throw new LogicException("Circular reference detected for service : '$name'.");
        }

        try {
            $this->underConstructionServices[] = $name;

            $definition = $this->library[$name];

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

            if (array_key_exists('class', $definition)) {
                $class = $definition['class'];
                $reflexionClass = new ReflectionClass($class);

                $service = $reflexionClass->newInstanceArgs($arguments);
            } elseif (array_key_exists('factory', $definition)) {
                $service = call_user_func_array(
                    $this->buildFactoryCallback($name, $definition['factory']),
                    $arguments
                );
            } else {
                throw new LogicException("Target service definition has no class name : '$name'.");
            }

            if (array_key_exists('calls', $definition)) {
                $subject = $this->library->isShared($name) ? null : $service;
                $this->callDelayer->addCalls($name, $definition['calls'], $subject);
            }

            if (array_key_exists('catch', $definition)) {
                $this->collectTaggedServices($service, $name, $definition);
            }

            if (array_key_exists('config', $definition)) {
                $this->setConfiguration($service, $name, $definition['config']);
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

    protected function buildFactoryCallback($name, $config)
    {
        if (!is_array($config)) {
            $config = array(
                'service' => $config
            );
        }

        if (!array_key_exists('method', $config)) {
            $config['method'] = 'build';
        }

        if (!array_key_exists('service', $config)) {
            throw new LogicException("Target service definition has no factory service name : '$name'.");
        }

        $factory = $this->container->get($config['service']);

        return array($factory, $config['method']);
    }

    protected function finalizeServiceConstruction($name)
    {
        $index = array_search($name, $this->underConstructionServices);

        unset($this->underConstructionServices[$index]);
    }

    /**
     * @param ConfigurableServiceInterface|object $service
     * @param string $name
     * @param string|array $config
     * @throws ParserConstantException
     * @throws ParserParameterException
     */
    protected function setConfiguration($service, $name, $config)
    {
        if (! $service instanceof ConfigurableServiceInterface) {
            throw new LogicException(
                "Service '$name' must implements ConfigurableServiceInterface interface to use 'config' key."
            );
        }

        $data = $this->parser->parseConfig($config);

        if (! is_array($data)) {
            throw new LogicException("Service '$name' has malformed configuration.");
        }

        $service->setConfig($config);
    }

    /**
     * @param object $service
     * @param string $name
     * @param array $definition
     * @throws Exception
     */
    protected function collectTaggedServices($service, $name, array $definition)
    {
        $catch = $definition['catch'];

        if (!is_array($catch)) {
            $catch = array(
                'tag' => $catch,
                'method' => 'addServiceName',
                'built' => false
            );
        }

        if (!array_key_exists('tag', $catch)) {
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
