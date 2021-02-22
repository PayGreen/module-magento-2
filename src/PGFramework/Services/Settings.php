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
 * @version   1.2.5
 *
 */

/**
 * Class PGFrameworkServicesSettings
 * @package PGFramework\Services
 */
class PGFrameworkServicesSettings extends PGFrameworkFoundationsAbstractObject
{
    /** @var array */
    private $definitions;

    /** @var array */
    private $config;

    /** @var PGFrameworkContainer */
    private $container;

    /** @var PGFrameworkInterfacesOfficersSettingsOfficerInterface */
    private $basicOfficer = null;

    /** @var PGFrameworkInterfacesOfficersSettingsOfficerInterface */
    private $globalOfficer = null;

    /** @var PGFrameworkInterfacesOfficersSettingsOfficerInterface */
    private $systemOfficer = null;

    /**
     * PGFrameworkServicesSettings constructor.
     * @param PGFrameworkContainer $container
     * @param array $config
     */
    public function __construct(PGFrameworkContainer $container, array $config)
    {
        $this->container = $container;
        $this->config = $config;
        $this->definitions = $config['entries'];
    }

    /**
     * @throws Exception
     */
    public function installDefault()
    {
        foreach (array_keys($this->definitions) as $key) {
            if ($this->hasDefault($key)) {
                $this->set($key, $this->getDefault($key));
            }
        }
    }

    /**
     * @throws Exception
     */
    public function uninstallSettings()
    {
        foreach (array_keys($this->definitions) as $key) {
            $this->remove($key);
        }
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function get($name)
    {
        $name = $this->getRealName($name);
        $hardName = $this->getHardName($name);

        try {
            $value = $this->getOfficer($name)->getOption($hardName, $this->getDefault($name));
        } catch (Exception $exception) {
            throw new Exception("An error occured when get '$name' setting.", 0, $exception);
        }

        $value = $this->unformat($name, $value);

        return $value;
    }

    /**
     * @param string $name
     * @return bool
     * @throws Exception
     */
    public function hasValue($name)
    {
        $value = $this->get($name);
        $test = false;

        if (($value !== null) && $this->hasDefault($name)) {
            $test = ($value !== $this->getDefault($name));
        }

        return $test;
    }

    /**
     * @param array $names
     * @return array
     * @throws Exception
     */
    public function getArray(array $names)
    {
        $data = array();

        foreach ($names as $name) {
            $data[$name] = $this->get($name);
        }

        return $data;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function set($name, $value)
    {
        $name = $this->getRealName($name);
        $value = $this->format($name, $value);

        $hardName = $this->getHardName($name);

        try {
            $this->getOfficer($name)->setOption($hardName, $value);
        } catch (Exception $exception) {
            throw new Exception("An error occured when set '$name' setting.", 0, $exception);
        }
    }

    /**
     * @param string $name
     * @throws Exception
     */
    public function reset($name)
    {
        $this->set($name, $this->getDefault($name));
    }

    /**
     * @param string $name
     * @throws Exception
     */
    public function remove($name)
    {
        $name = $this->getRealName($name);
        $hardName = $this->getHardName($name);

        try {
            $this->getOfficer($name)->unsetOption($hardName);
        } catch (Exception $exception) {
            throw new Exception("An error occured when remove '$name' setting.", 0, $exception);
        }
    }

    /**
     * @param string $name
     * @return PGFrameworkInterfacesOfficersSettingsOfficerInterface
     * @throws Exception
     */
    protected function getOfficer($name)
    {
        /** @var PGFrameworkInterfacesOfficersSettingsOfficerInterface $officer */
        $officer = null;

        if ($this->isSystem($name)) {
            if ($this->systemOfficer === null) {
                $this->systemOfficer = $this->container->get($this->config['officers']['system']);
            }

            $officer = $this->systemOfficer;
        } elseif ($this->isGlobal($name)) {
            if ($this->globalOfficer === null) {
                $this->globalOfficer = $this->container->get($this->config['officers']['global']);
            }

            $officer = $this->globalOfficer;
        } else {
            if ($this->basicOfficer === null) {
                $this->basicOfficer = $this->container->get($this->config['officers']['basic']);
            }

            $officer = $this->basicOfficer;
        }

        return $officer;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isDefined($name)
    {
        return array_key_exists($name, $this->definitions);
    }

    /**
     * @param string $name
     * @return array
     * @throws Exception
     */
    protected function getDefinition($name)
    {
        if (!$this->isDefined($name)) {
            throw new Exception("Undefined setting : '$name'.");
        }

        return $this->definitions[$name];
    }

    /**
     * @return array
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * @param string $name
     * @return string
     * @throws Exception
     */
    protected function getRealName($name)
    {
        if (array_key_exists($name, $this->definitions)) {
            return $name;
        }

        foreach (array_keys($this->definitions) as $key) {
            if ($this->getHardName($key) === $name) {
                return $key;
            }
        }

        throw new Exception("Unknown setting name : '$name'.");
    }

    /**
     * @param string $name
     * @return string
     * @throws Exception
     */
    protected function getHardName($name)
    {
        $definition = $this->getDefinition($name);

        return array_key_exists('alias', $definition) ? $definition['alias'] : $name;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    protected function getDefault($name)
    {
        $definition = $this->getDefinition($name);

        return array_key_exists('default', $definition) ? $definition['default'] : null;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function isGlobal($name)
    {
        $definition = $this->getDefinition($name);

        return array_key_exists('global', $definition) ? (bool) $definition['global'] : false;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function isSystem($name)
    {
        $definition = $this->getDefinition($name);

        return array_key_exists('system', $definition) ? (bool) $definition['system'] : false;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    protected function hasDefault($name)
    {
        $definition = $this->getDefinition($name);

        return array_key_exists('default', $definition);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return string
     * @throws Exception
     */
    protected function format($name, $value)
    {
        $definitions = $this->getDefinition($name);

        if (array_key_exists('type', $definitions)) {
            switch ($definitions['type']) {
                case 'array':
                    if (!is_array($value)) {
                        throw new Exception("'$name' parameters should be an array.");
                    }

                    $value = serialize($value);

                    break;
                default:
                    $value = trim((string) $value);
            }
        }

        return $value;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @throws Exception
     */
    protected function unformat($name, $value)
    {
        $definitions = $this->getDefinition($name);

        if (array_key_exists('type', $definitions)) {
            switch ($definitions['type']) {
                case 'array':
                    if (!is_array($value)) {
                        $value = (array) unserialize($value);
                    }
                    break;
                case 'int':
                    $value = (int) $value;
                    break;
                case 'string':
                    $value = (string) $value;
                    break;
                case 'bool':
                    $value = (bool) $value;
                    break;
                case 'float':
                    $value = (float) $value;
                    break;
                default:
                    $value = trim($value);
            }
        }

        return $value;
    }
}
