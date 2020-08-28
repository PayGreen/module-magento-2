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
 * @version   1.0.1
 */

class PGFrameworkComponentsUpgradeStage
{
    private $name;

    private $version;

    private $config = array();

    private $priority;

    private $type;

    /**
     * PGFrameworkComponentsUpgradeStage constructor.
     * @param string $name
     * @param array $data
     * @throws Exception
     */
    public function __construct($name, array $data)
    {
        if (!array_key_exists('type', $data)) {
            throw new Exception("Upgrade '$name' require 'type' parameter.");
        } elseif (!array_key_exists('version', $data)) {
            throw new Exception("Upgrade '$name' require 'version' parameter.");
        }

        $this->name = $name;
        $this->type = $data['type'];
        $this->version = $data['version'];
        $this->priority = array_key_exists('priority', $data) ? $data['priority'] : PGFrameworkServicesUpgrader::DEFAULT_PRIORITY;
        $this->config = array_key_exists('config', $data) ? $data['config'] : array();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int|mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return mixed
     */
    public function getConfig($key)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : null;
    }

    public function greaterThan($version)
    {
        return (PGFrameworkToolsVersion::compare($this->getVersion(), $version) === 1);
    }

    public function greaterOrEqualThan($version)
    {
        return (PGFrameworkToolsVersion::compare($this->getVersion(), $version) !== -1);
    }

    public function lesserThan($version)
    {
        return (PGFrameworkToolsVersion::compare($this->getVersion(), $version) === -1);
    }

    public function lesserOrEqualThan($version)
    {
        return (PGFrameworkToolsVersion::compare($this->getVersion(), $version) !== 1);
    }
}
