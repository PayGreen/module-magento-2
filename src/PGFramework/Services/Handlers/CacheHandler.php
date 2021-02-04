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

class PGFrameworkServicesHandlersCacheHandler
{
    const DEFAULT_TTL = 60;

    /** @var array */
    private $entries = array();

    /** @var array */
    private $cached_entries = array();

    /** @var PGFrameworkServicesPathfinder */
    private $pathfinder;

    /** @var PGFrameworkServicesSettings */
    private $settings;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    public function __construct(
        array $parameters,
        PGFrameworkServicesPathfinder $pathfinder,
        PGFrameworkServicesSettings $settings,
        PGFrameworkServicesLogger $logger
    ) {
        $this->entries = $parameters['entries'];

        $this->pathfinder = $pathfinder;
        $this->settings = $settings;
        $this->logger = $logger;

        if ($this->isActivate()) {
            $this->logger->debug("Cache handler initialized.");
        }
    }

    public function isActivate()
    {
        $varFolder = $this->pathfinder->getBasePath('cache');
        $useCache = $this->settings->get('use_cache');

        return ($useCache && is_dir($varFolder) && is_writable($varFolder));
    }

    public function loadEntry($name)
    {
        $data = null;

        if (!isset($this->entries[$name])) {
            $this->logger->warning("Undefined entry cache : '$name'.");
            return $data;
        }

        if (array_key_exists($name, $this->cached_entries)) {
            $this->logger->debug("Reading entry '$name' from cache handler.");
            $data = $this->cached_entries[$name];
        } elseif ($this->isActivate() && $this->hasValidEntry($name)) {
            $path = $this->getPath($name);

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

            $this->cached_entries[$name] = $data;
        }

        return $data;
    }

    public function saveEntry($name, $data)
    {
        $path = $this->getPath($name);

        if (!isset($this->entries[$name])) {
            throw new Exception("Undefined entry cache : '$name'.");
        }

        $this->cached_entries[$name] = $data;

        if ($this->isActivate()) {
            $this->logger->debug("Saving entry '$name' in '$path'.");

            $content = json_encode($data);

            $result = file_put_contents($path, $content);

            if ($result === false) {
                throw new Exception("Unable to save entry '$name' in path '$path'.");
            }

            $this->logger->debug("$result octets saved in '$path' for entry '$name'.");
        }
    }

    public function clearCache()
    {
        $this->cached_entries = array();

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

        $hasEntry = $this->hasEntry($name, $path);

        if (!$hasEntry) {
            $this->logger->warning("File not found for entry '$name' : '$path'.");
        }

        return $hasEntry && !$this->isExpiredEntry($name, $path);
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

        $filetime = filemtime($path);

        $timestamp = $dt->getTimestamp();

        $isExpired = $filetime < $timestamp;

        if ($isExpired) {
            $this->logger->warning("Detect expired entry for '$name' with TTL of $ttl.");
        }

        return $isExpired;
    }

    protected function getPath($name)
    {
        if (defined('PAYGREEN_CACHE_PREFIX')) {
            $name = PAYGREEN_CACHE_PREFIX . '.' . $name;
        }

        return $this->pathfinder->toAbsolutePath('cache', "/entry.$name.cache.json");
    }
}
