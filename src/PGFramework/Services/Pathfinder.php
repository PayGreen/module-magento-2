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
 * Class PGFrameworkServicesPathfinder
 * @package PGFramework\Services
 */
class PGFrameworkServicesPathfinder
{
    private $appliance;

    private $bases = array();

    public function __construct(PGFrameworkComponentsAppliance $appliance, array $bases = array())
    {
        $this->appliance = $appliance;

        foreach ($bases as $name => $path) {
            $this->addBase($name, $path);
        }
    }

    public function addBase($name, $path)
    {
        $this->bases[$name] = $this->formatPath($path);
    }

    /**
     * @return array
     */
    public function getBases()
    {
        return array_keys($this->bases);
    }

    /**
     * @param string $base
     * @return mixed
     * @throws Exception
     */
    public function getBasePath($base)
    {
        if (!array_key_exists($base, $this->bases)) {
            throw new Exception("Unknown path origin : '$base'.");
        }

        return $this->bases[$base];
    }

    /**
     * @param string $base
     * @param string $src
     * @return string
     * @throws Exception
     */
    public function toAbsolutePath($base, $src = '')
    {
        if (empty($src) && strstr($base, ':') !== false) {
            list($base, $src) = explode(':', $base, 2);
        }

        return $this->getBasePath($base) . $this->formatPath($src);
    }

    public function formatPath($src)
    {
        $tokens = explode('/', $src);

        return implode(DIRECTORY_SEPARATOR, $tokens);
    }

    public function reviewVendorPaths($src)
    {
        $paths = array();

        $vendors = $this->appliance->getVendors();

        foreach ($vendors as $vendor) {
            $path = $this->toAbsolutePath($vendor, $src);

            if (is_dir($path)) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    public function searchPath($src)
    {
        $vendors = $this->appliance->getVendors();

        foreach ($vendors as $vendor) {
            $path = $this->toAbsolutePath($vendor, $src);

            if (is_file($path) || is_dir($path)) {
                return $path;
            }
        }

        return null;
    }

    public function searchPaths($src)
    {
        $vendors = $this->appliance->getVendors();

        $files = array();

        foreach ($vendors as $vendor) {
            $path = $this->toAbsolutePath($vendor, $src);

            if (is_file($path) || is_dir($path)) {
                $files[$vendor] = $path;
            }
        }

        return $files;
    }
}
