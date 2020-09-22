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

class PGFormServicesFieldBuilder
{
    /** @var PGFrameworkContainer */
    private $container;

    /** @var PGFormServicesValidatorBuilder */
    private $builderValidator;

    /** @var PGFormServicesFormatterBuilder */
    private $formatterBuilder;

    /** @var PGFrameworkServicesHandlersBehaviorHandler */
    private $behaviorHandler;

    /** @var PGViewServicesBuildersViewBuilder */
    private $viewBuilder;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    private $config;

    public function __construct(
        PGFrameworkContainer $container,
        PGFormServicesValidatorBuilder $builderValidator,
        PGFormServicesFormatterBuilder $formatterBuilder,
        PGFrameworkServicesHandlersBehaviorHandler $behaviorHandler,
        PGViewServicesBuildersViewBuilder $viewBuilder,
        PGFrameworkServicesLogger $logger,
        array $config
    ) {
        $this->container = $container;
        $this->builderValidator = $builderValidator;
        $this->formatterBuilder = $formatterBuilder;
        $this->behaviorHandler = $behaviorHandler;
        $this->viewBuilder = $viewBuilder;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @param string $name
     * @param array $config
     * @return PGFormInterfacesFieldInterface|null
     * @throws ReflectionException
     * @throws Exception
     */
    public function build($name, array $config = array())
    {
        $config = $this->buildFieldConfiguration($config);

        if (!$this->isFieldEnabled($config)) {
            return null;
        }

        /** @var PGFormInterfacesFieldInterface $field */
        $field = $this->instanciateField($name, $config);

        $this->insertValidators($field, $config);
        $this->insertFormatter($field, $config);
        $this->insertChildren($field, $config);

        $field->setViewBuilder($this->viewBuilder);

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

        PGFrameworkToolsArray::merge($fieldConfig, $config);

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
        } elseif (($config['behavior'] !== null) && !$this->behaviorHandler->get($config['behavior'])) {
            return false;
        }

        return true;
    }

    /**
     * @param string $name
     * @param array $config
     * @return PGFormInterfacesFieldInterface
     * @throws ReflectionException
     */
    protected function instanciateField($name, array $config)
    {
        $class = $this->getFieldClass($config);

        $reflexion = new ReflectionClass($class);

        /** @var PGFormInterfacesFieldInterface $field */
        $field = $reflexion->newInstance($name, $config);

        if (! $field instanceof PGFormInterfacesFieldInterface) {
            throw new Exception("$class must implements PGFormInterfacesFieldInterface interface.");
        }

        if ($field instanceof PGFormInterfacesFieldArrayInterface) {
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

    protected function insertValidators(PGFormInterfacesFieldInterface $field, array $config)
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

    protected function insertFormatter(PGFormInterfacesFieldInterface $field, array $config)
    {
        if (!array_key_exists('format', $config)) {
            $this->logger->alert("Invalid field configuration !!", $config);
            throw new Exception("Field key 'format' not found.");
        } elseif (!is_string($config['format'])) {
            $this->logger->alert("Invalid field configuration !!", $config);
            throw new Exception("Field key 'format' must be a string.");
        }

        $formatter = $this->formatterBuilder->getFormatter($config['format']);

        $field->setFormatter($formatter);
    }

    protected function insertChildren(PGFormInterfacesFieldInterface $field, array $config)
    {
        if (array_key_exists('children', $config)) {
            if (!is_array($config['children'])) {
                $this->logger->alert("Invalid field configuration !!", $config);
                throw new Exception("Field key 'children' must be an array.");
            } elseif (! $field instanceof PGFormInterfacesFieldObjectInterface) {
                throw new Exception("A child cannot be inserted into a field that does not implement the PGFormInterfacesFieldObjectInterface interface.");
            }

            foreach ($config['children'] as $childName => $childConfig) {
                $child = $this->build($childName, $childConfig);

                $field->addChild($child);
            }
        }
    }
}
