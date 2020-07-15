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
 * @version   1.0.0
 */

/**
 * Class PGFrameworkServicesAutoloader
 * @package PGFramework\Services
 */
class PGFrameworkServicesAutoloader
{
    /** @var array */
    private $vendors;

    /** @var PGFrameworkInterfacesStorageInterface  */
    private $classNames;

    public function __construct(PGFrameworkInterfacesStorageInterface $storage)
    {
        $this->classNames = $storage;
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
     * @return array
     */
    public function getVendors()
    {
        return $this->vendors;
    }

    /**
     * @param $className
     * @return bool
     * @throws Exception
     */
    public function autoload($className)
    {
        if (isset($this->classNames[$className])) {
            $src = $this->classNames[$className];

            if (file_exists($src)) {
                $this->loadFile($src);
                return true;
            }
        }

        foreach ($this->vendors as $name => $vendor) {
            $pattern = "/^{$name}/";

            if (preg_match($pattern, $className) === 1) {
                $formatedClassName = substr($className, strlen($name));
                $formatedClassName = $this->snakify($formatedClassName);

                $src = $this->getFilename($formatedClassName, $vendor['path']);

                $this->loadFile($src);

                $this->extendCache($className, $src);

                return true;
            }
        }

        return false;
    }

    /**
     * @param $src string
     * @throws Exception
     */
    protected function loadFile($src)
    {
        if (!is_file($src)) {
            throw new Exception("File not found : '$src'.");
        }

        require_once($src);
    }

    protected function snakify($className)
    {
        return preg_replace("/([a-z0-9])([A-Z])/", '$1_$2', $className);
    }

    protected function getFilename($className, $basePath)
    {
        $tokens = explode('_', $className);

        $tokens = $this->pathFinderTokenParse($tokens, $basePath);

        array_unshift($tokens, $basePath);

        return implode(DIRECTORY_SEPARATOR, $tokens) . '.php';
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
