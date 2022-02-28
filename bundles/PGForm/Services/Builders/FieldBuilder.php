<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGForm\Services\Builders;

use PGI\Module\PGForm\Interfaces\Fields\BasicFieldInterface;
use PGI\Module\PGForm\Interfaces\Fields\CollectionFieldInterface;
use PGI\Module\PGForm\Interfaces\Fields\CompositeFieldInterface;
use PGI\Module\PGForm\Interfaces\FormatterInterface;
use PGI\Module\PGForm\Services\Builders\ValidatorBuilder;
use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use PGI\Module\PGModule\Services\Handlers\BehaviorHandler;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGSystem\Services\Container;
use PGI\Module\PGSystem\Tools\Collection as CollectionTool;
use PGI\Module\PGFramework\Services\Handlers\RequirementHandler;
use Exception;
use ReflectionException;
use ReflectionClass;

/**
 * Class Container
 * @package PGForm\Services\Builders
 */
class FieldBuilder
{
    /** @var Container */
    private $container;

    /** @var ValidatorBuilder */
    private $builderValidator;

    /** @var AggregatorComponent */
    private $formatterAggregator;

    /** @var BehaviorHandler */
    private $behaviorHandler;

    /** @var AggregatorComponent */
    private $viewAggregator;

    /** @var LoggerInterface */
    private $logger;

    /** @var RequirementHandler */
    private $requirementHandler;

    private $config;

    public function __construct(
        Container           $container,
        ValidatorBuilder    $builderValidator,
        AggregatorComponent $formatterAggregator,
        BehaviorHandler     $behaviorHandler,
        AggregatorComponent $viewAggregator,
        LoggerInterface              $logger,
        array               $config,
        RequirementHandler $requirementHandler
    ) {
        $this->container = $container;
        $this->builderValidator = $builderValidator;
        $this->formatterAggregator = $formatterAggregator;
        $this->behaviorHandler = $behaviorHandler;
        $this->viewAggregator = $viewAggregator;
        $this->logger = $logger;
        $this->config = $config;
        $this->requirementHandler = $requirementHandler;
    }

    /**
     * @param string $name
     * @param array $config
     * @return BasicFieldInterface|null
     * @throws ReflectionException
     * @throws Exception
     */
    public function build($name, array $config = array())
    {
        $config = $this->buildFieldConfiguration($config);

        if (!$this->isFieldEnabled($config)) {
            return null;
        }

        /** @var BasicFieldInterface $field */
        $field = $this->instanciateField($name, $config);

        $this->insertValidators($field, $config);
        $this->insertFormatter($field, $config);
        $this->insertChildren($field, $config);

        $field->setViewAggregator($this->viewAggregator);

        if (array_key_exists('default', $config)) {
            $field->setValue($config['default']);
        }

        $field->init();

        return $field;
    }

    protected function buildFieldConfiguration(array $config)
    {
        if (array_key_exists('model', $config)) {
            $model = $config['model'];

            if (!array_key_exists($model, $this->config['models'])) {
                throw new Exception("Field model '$model' not found.");
            }

            $fieldConfig = $this->config['models'][$model];
            $fieldConfig = $this->buildFieldConfiguration($fieldConfig);
        } else {
            $fieldConfig = $this->config['default'];
        }

        CollectionTool::merge($fieldConfig, $config);

        return $fieldConfig;
    }

    /**
     * @param array $config
     * @return bool
     * @throws Exception
     */
    protected function isFieldEnabled(array $config)
    {
        if ($config['enabled'] === false) {
            return false;
        } elseif (!empty($config['requirements'])) {
            $this->logger->debug("Requirement", $config['requirements']);
            return $this->requirementHandler->areFulfilled($config['requirements']);
        }

        return true;
    }

    /**
     * @param string $name
     * @param array $config
     * @return BasicFieldInterface
     * @throws ReflectionException
     */
    protected function instanciateField($name, array $config)
    {
        $class = $this->getFieldClass($config);

        $reflexion = new ReflectionClass($class);

        /** @var BasicFieldInterface $field */
        $field = $reflexion->newInstance($name, $config);

        if (! $field instanceof BasicFieldInterface) {
            throw new Exception("$class must implements BasicFieldInterface interface.");
        }

        if ($field instanceof CollectionFieldInterface) {
            $field->setFieldBuilder($this);
        }

        return $field;
    }

    /**
     * @param array $config
     * @return string
     * @throws Exception
     */
    protected function getFieldClass(array $config)
    {
        if (array_key_exists('type', $config)) {
            $type = $config['type'];
        } else {
            throw new Exception("Unable to find default field type in FieldBuilder configuration.");
        }

        if (!array_key_exists('types', $this->config)) {
            throw new Exception("Unable to find field types in FieldBuilder configuration.");
        } elseif (!array_key_exists($type, $this->config['types'])) {
            throw new Exception("Unable to find field type '$type' in FieldBuilder configuration.");
        } else {
            $class = $this->config['types'][$type];
        }

        return $class;
    }

    protected function insertValidators(BasicFieldInterface $field, array $config)
    {
        if (array_key_exists('validators', $config)) {
            if (!is_array($config['validators'])) {
                throw new Exception("Field key 'validators' must be an array.");
            }

            foreach ($config['validators'] as $type => $validatorConfig) {
                $validator = $this->builderValidator->build($type, $validatorConfig);

                $field->addValidator($validator);
            }
        }
    }

    protected function insertFormatter(BasicFieldInterface $field, array $config)
    {
        if (!array_key_exists('format', $config)) {
            $this->logger->alert("Invalid field configuration !!", $config);
            throw new Exception("Field key 'format' not found.");
        } elseif (!is_string($config['format'])) {
            $this->logger->alert("Invalid field configuration !!", $config);
            throw new Exception("Field key 'format' must be a string.");
        }

        /** @var FormatterInterface $formatter */
        $formatter = $this->formatterAggregator->getService($config['format']);

        $field->setFormatter($formatter);
    }

    protected function insertChildren(BasicFieldInterface $field, array $config)
    {
        if (array_key_exists('children', $config)) {
            if (!is_array($config['children'])) {
                $this->logger->alert("Invalid field configuration !!", $config);
                throw new Exception("Field key 'children' must be an array.");
            } elseif (! $field instanceof CompositeFieldInterface) {
                throw new Exception(
                    "A child can't be inserted into a field that don't implement CompositeFieldInterface."
                );
            }

            foreach ($config['children'] as $childName => $childConfig) {
                $child = $this->build($childName, $childConfig);

                $field->addChild($child);
            }
        }
    }
}
