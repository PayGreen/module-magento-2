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
 * @version   0.3.2
 */

class PGFrameworkServicesHandlersCacheHandler
{
    const DEFAULT_TTL = 60;

    /** @var array */
    private $entries = array();

    /** @var array */
    private $config = array();

    /** @var PGFrameworkServicesLogger */
    private $logger;

    public function __construct(array $parameters, PGFrameworkServicesLogger $logger)
    {
        $this->config = $parameters['config'];
        $this->entries = $parameters['entries'];

        $this->logger = $logger;

        $this->logger->debug("Cache handler initialized.");
    }

    public function isActivate()
    {
        return ($this->config['activate'] === true);
    }

    public function loadEntry($name)
    {
        $path = $this->getPath($name);
        $data = null;

        if (!isset($this->entries[$name])) {
            $this->logger->warning("Undefined entry cache : '$name'.");
            return $data;
        }

        if ($this->isActivate() && $this->hasValidEntry($name)) {
            $this->logger->debug("Reading entry '$name' in '$path'.");

            $content = file_get_contents($path);

            $format = isset($this->entries[$name]['format']) ? $this->entries[$name]['format'] : 'array';

            switch ($format) {
                case 'array':
                    $data = json_decode($content, true);
                    break;
                case 'object':
                    $data = json_decode($content);
                    break;
                default:
                    $this->logger->warning("Unknown entry cache format : '$format'.");
            }
        }

        return $data;
    }

    public function saveEntry($name, $data)
    {
        $path = $this->getPath($name);

        if (!isset($this->entries[$name])) {
            throw new Exception("Undefined entry cache : '$name'.");
        }

        if ($this->isActivate()) {
            $this->logger->debug("Saving entry '$name' in '$path'.");

            if ($this->hasEntry($name, $path)) {
                unlink($path);
            }

            $content = json_encode($data);

            file_put_contents($path, $content);
        }
    }

    public function clearCache()
    {
        foreach (array_keys($this->entries) as $name) {
            $path = $this->getPath($name);

            if ($this->hasEntry($name, $path)) {
                unlink($path);
            }
        }
    }

    /**
     * @param $name
     * @return bool
     * @todo Implements and add ttl management
     */
    protected function hasValidEntry($name)
    {
        $path = $this->getPath($name);

        return $this->hasEntry($name, $path) && !$this->isExpiredEntry($name, $path);
    }

    protected function hasEntry($name, $path = null)
    {
        $path = ($path !== null) ? $path : $this->getPath($name);

        return file_exists($path);
    }

    protected function isExpiredEntry($name, $path = null)
    {
        $ttl = isset($this->entries[$name]['ttl']) ? $this->entries[$name]['ttl'] : self::DEFAULT_TTL;

        $dt = new DateTime("-$ttl seconds");

        return filemtime($path) < $dt->getTimestamp();
    }

    protected function getPath($name)
    {
        if (defined('PAYGREEN_CACHE_PREFIX')) {
            $name = PAYGREEN_CACHE_PREFIX . '.' . $name;
        }

        return PAYGREEN_VAR_DIR . DIRECTORY_SEPARATOR . 'entry.' . $name . '.cache.php';
    }
}
