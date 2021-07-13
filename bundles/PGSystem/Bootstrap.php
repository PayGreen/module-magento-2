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
 * @version   2.1.1
 *
 */

/**
 * Class PGSystemBootstrap
 * @package PGSystem
 */
class PGSystemBootstrap
{
    const VAR_FOLDER_CHMOD = 0775;

    /** @var PGSystemServicesAutoloadersUncamelified */
    private $autoloader = null;

    /** @var PGSystemServicesPathfinder */
    private $pathfinder = null;

    /** @var PGSystemServicesContainer */
    private $container = null;

    /** @var PGSystemComponentsKernel */
    private $kernel = null;

    private static $required_paths = array(
        'module',
        'var',
        'log',
        'data',
        'cache',
        'media',
        'static',
        'templates'
    );

    private static $required_constants = array(
        'PAYGREEN_VENDOR_DIR',
        'PAYGREEN_MEDIA_DIR',
        'PAYGREEN_MODULE_VERSION'
    );

    private static $default_created_folders = array(
        'var',
        'log',
        'cache'
    );

    /**
     * PGSystemBootstrap constructor.
     * @param string $path The path to all bundle folders.
     * @throws Exception
     */
    public function __construct()
    {
        define('PAYGREEN_ENV', getenv('PAYGREEN_ENV') ? strtoupper(getenv('PAYGREEN_ENV')) : 'PROD');

        foreach (self::$required_constants as $constant) {
            if (!defined($constant)) {
                throw new Exception("'$constant' is not defined.");
            }
        }
    }

    /**
     * @return PGSystemComponentsKernel
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * @return PGSystemServicesContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return PGSystemServicesAutoloadersUncamelified
     */
    public function getAutoloader()
    {
        return $this->autoloader;
    }

    /**
     * @return PGSystemServicesPathfinder
     */
    public function getPathfinder()
    {
        return $this->pathfinder;
    }

    /**
     * @param string $path
     * @param string|null $subset
     * @return self
     * @throws Exception
     */
    public function buildKernel($path, $subset = null)
    {
        $kernelBuilder = new PGSystemComponentsBuildersKernel();

        if ($subset) {
            $src = $path . DIRECTORY_SEPARATOR . "bundles-$subset.php";
        } else {
            $src = $path . DIRECTORY_SEPARATOR . 'bundles.php';
        }

        $this->kernel = $kernelBuilder->buildKernel($src);

        return $this;
    }

    /**
     * @param array $paths
     * @return self
     * @throws Exception
     */
    public function buildPathfinder(array $paths)
    {
        if ($this->kernel === null) {
            throw new Exception("Kernel must be builded before building pathfinder.");
        }

        $this->verifyPaths($paths);

        $this->pathfinder = new PGSystemServicesPathfinder($this->kernel, $paths);

        return $this;
    }

    /**
     * @param array $paths
     * @throws Exception
     */
    protected function verifyPaths(array $paths)
    {
        foreach (self::$required_paths as $pathName) {
            if (!isset($paths[$pathName])) {
                throw new Exception("Path not found : '$pathName'.");
            }
        }
    }

    /**
     * @param array $additionalFolders
     * @return self
     * @throws Exception
     */
    public function createVarFolder(array $additionalFolders = array())
    {
        if ($this->pathfinder === null) {
            throw new Exception("PathFinder must be initialized before loading functions.");
        }

        $targets = array_merge(
            self::$default_created_folders,
            $additionalFolders
        );

        foreach ($targets as $target) {
            $path = $this->pathfinder->toAbsolutePath($target);

            if (!is_dir($path)) {
                @mkdir($path, self::VAR_FOLDER_CHMOD, true);
            }
        }

        return $this;
    }

    /**
     * @param string $builderName
     * @return self
     * @throws Exception
     */
    public function registerAutoloader($builderName = 'PGSystemComponentsBuildersAutoloaderBundles')
    {
        /** @var PGSystemInterfacesBootstrapBuilder $autoloaderBuilder */
        $autoloaderBuilder = new $builderName($this);

        $this->autoloader = $autoloaderBuilder->build();

        spl_autoload_register(array($this->autoloader, 'autoload'), true, true);

        return $this;
    }

    /**
     * @return self
     * @throws Exception
     */
    public function buildContainer()
    {
        if ($this->pathfinder === null) {
            throw new Exception("PathFinder must be initialized before loading functions.");
        }

        $containerBuilder = new PGSystemComponentsBuildersContainer($this->pathfinder);

        $this->container = $containerBuilder->buildContainer();

        return $this;
    }

    /**
     * @param array $additionalServices
     * @return self
     * @throws Exception
     */
    public function insertStaticServices(array $additionalServices = array())
    {
        if ($this->container === null) {
            throw new Exception("Container must be initialized before inserting services.");
        }

        $defaultServices = array(
            'kernel' => $this->kernel,
            'pathfinder' => $this->pathfinder,
            'bootstrap' => $this
        );

        if (!empty($this->autoloader)) {
            $defaultServices['autoloader'] = $this->autoloader;
        }

        $services = array_merge(
            $defaultServices,
            $additionalServices
        );

        foreach ($services as $name => $service) {
            $this->container->set($name, $service);
        }

        return $this;
    }

    /**
     * @param int $flags
     * @return self
     * @throws Exception
     */
    public function setup($flags = PGModuleServicesHandlersSetup::ALL)
    {
        if ($this->container === null) {
            throw new Exception("Container must be initialized before running setup.");
        }

        /** @var PGModuleServicesHandlersSetup $setupHandler */
        $setupHandler = $this->container->get('handler.setup');

        $setupHandler->run($flags);

        return $this;
    }
}
