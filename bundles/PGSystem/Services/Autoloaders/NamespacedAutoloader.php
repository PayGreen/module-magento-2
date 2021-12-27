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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGSystem\Services\Autoloaders;

use PGI\Module\PGSystem\Services\Pathfinder;
use Exception;

/**
 * Class NamespacedAutoloader
 * @package PGSystem\Services\Autoloaders
 */
class NamespacedAutoloader
{
    const PREFIX = 'PGI\Module\\';

    /** @var array */
    private $vendors;

    /** @var Pathfinder */
    private $pathfinder;

    public function __construct(Pathfinder $pathfinder)
    {
        $this->pathfinder = $pathfinder;
    }

    /**
     * @param string $name
     * @param string $basePath
     * @param array $options
     * @return $this
     */
    public function addVendor($name, $basePath, $options = array())
    {
        $this->vendors[$name] = array(
            'path' => $basePath,
            'options' => $options
        );

        return $this;
    }

    /**
     * @param $className
     * @return bool
     * @throws Exception
     */
    public function autoload($className)
    {
        echo $className;
        if (strpos(self::PREFIX, $className) === 0) {
            $className = substr($className, strlen(self::PREFIX));
            $vendorName = current(explode('\\', $className));
            $className = substr($className, strlen($vendorName) + 1);

            if (!isset($this->vendors[$vendorName])) {
                throw new Exception("Unknown vendor name : '$vendorName'.");
            }

            $relativePath = str_replace('\\', '/', $className) . '.php';

            if ($this->loadFile("$vendorName:$relativePath")) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $path
     * @throws Exception
     */
    protected function loadFile($path)
    {
        $src = $this->pathfinder->toAbsolutePath($path);

        if (!is_file($src)) {
            throw new Exception("File not found : '$src'.");
        }

        require_once($src);
    }
}
