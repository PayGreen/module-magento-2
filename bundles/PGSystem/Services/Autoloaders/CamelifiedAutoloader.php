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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGSystem\Services\Autoloaders;

use PGI\Module\PGSystem\Interfaces\StorageInterface;
use PGI\Module\PGSystem\Services\Pathfinder;
use Exception;

/**
 * Class CamelifiedAutoloader
 * @package PGSystem\Services\Autoloaders
 */
class CamelifiedAutoloader
{
    /** @var array */
    private $vendors;

    /** @var StorageInterface  */
    private $classNames;

    /** @var Pathfinder */
    private $pathfinder;

    public function __construct(StorageInterface $storage, Pathfinder $pathfinder)
    {
        $this->classNames = $storage;
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
        if (isset($this->classNames[$className])) {
            $path = $this->classNames[$className];

            if ((strstr($path, ':') !== false) && $this->loadFile($path, false)) {
                return true;
            }
        }

        foreach ($this->vendors as $name => $vendor) {
            $pattern = "/^{$name}/";

            if (preg_match($pattern, $className) === 1) {
                $formatedClassName = substr($className, strlen($name));
                $formatedClassName = $this->snakify($formatedClassName);

                $relativePath = $this->getRelativePath($formatedClassName, $vendor['path']);

                $path = "$name:$relativePath";

                if ($this->loadFile($path, false)) {
                    $this->extendCache($className, $path);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param string $path
     * @param bool $isStrict
     * @return bool
     * @throws Exception
     */
    protected function loadFile($path, $isStrict = true)
    {
        $src = $this->pathfinder->toAbsolutePath($path);

        if (!is_file($src)) {
            if (!$isStrict) {
                return false;
            }

            throw new Exception("File not found : '$src'.");
        }

        require_once($src);

        return true;
    }

    protected function snakify($className)
    {
        return preg_replace("/([a-z0-9])([A-Z])/", '$1_$2', $className);
    }

    protected function getRelativePath($className, $basePath)
    {
        $tokens = explode('_', $className);

        $tokens = $this->pathFinderTokenParse($tokens, $basePath);

        return '/' . implode('/', $tokens) . '.php';
    }

    protected function pathFinderTokenParse($tokens, $folder)
    {
        $directories = array();
        $directory = null;
        $lastIndex = count($tokens) - 1;
        $fileIndex = 0;

        foreach ($tokens as $index => $token) {
            if ($index === $lastIndex) {
                break;
            }

            $directory = ($directory === null) ? $token : $directory . $token;

            $folderTokens = array_merge(array($folder), $directories, array($directory));
            $path = implode(DIRECTORY_SEPARATOR, $folderTokens);

            if (is_dir($path)) {
                $directories[] = $directory;
                $directory = null;
                $fileIndex = $index + 1;
            }
        }

        $filename = implode('', array_slice($tokens, $fileIndex));
        $tokens = array_merge($directories, array($filename));

        return $tokens;
    }

    protected function extendCache($className, $src)
    {
        $this->classNames[$className] = $src;
    }
}
