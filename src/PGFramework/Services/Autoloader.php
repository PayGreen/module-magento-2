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
 */

/**
 * Class PGFrameworkServicesAutoloader
 * @package PGFramework\Services
 */
class PGFrameworkServicesAutoloader
{
    /** @var array */
    private $vendors;

    private $classNames = array();

    public function __construct()
    {
        $filename = $this->getCacheFilename();

        if (file_exists($filename)) {
            $content = file_get_contents($filename);
            $classNames = json_decode($content, true);

            if (is_array($classNames)) {
                $this->classNames = $classNames;
            }
        }
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
        if (array_key_exists($className, $this->classNames)) {
            $this->loadFile($this->classNames[$className]);

            return true;
        } else {
            foreach ($this->vendors as $name => $vendor) {
                $pattern = "/^{$name}/";

                if (preg_match($pattern, $className) === 1) {
                    $formatedClassName = $this->snakify($className);

                    $src = $this->getFilename($formatedClassName, $vendor['path']);

                    $this->loadFile($src);

                    $this->extendCache($className, $src);

                    return true;
                }
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

        array_shift($tokens);

        $tokens = $this->pathFinderTokenParse($tokens, $basePath);

        array_unshift($tokens, $basePath);

        return implode(DIRECTORY_SEPARATOR, $tokens) . '.php';
    }

    protected function pathFinderTokenParse($tokens, $folder)
    {
        foreach ($tokens as $index => $token) {
            if (is_dir($folder . DIRECTORY_SEPARATOR . $token)) {
                $folder .= DIRECTORY_SEPARATOR . $token;
            } else {
                $filename = implode('', array_slice($tokens, $index));
                $tokens = array_merge(array_slice($tokens, 0, $index), array($filename));

                break;
            }
        }

        return $tokens;
    }

    protected function getCacheFilename()
    {
        return PAYGREEN_VAR_DIR . DIRECTORY_SEPARATOR . 'autoload.cache.json';
    }

    protected function extendCache($className, $src)
    {
        $this->classNames[$className] = $src;

        $cache = json_encode($this->classNames);

        $handler = @fopen($this->getCacheFilename(), "w+");

        if ($handler && flock($handler, LOCK_EX | LOCK_NB)) {
            ftruncate($handler, 0);
            fwrite($handler, $cache);
            flock($handler, LOCK_UN);
        }
    }
}
