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

/**
 * Class PGFrameworkBootstrap
 * @package PGFramework
 */
class PGFrameworkBootstrap
{
    /** @var PGFrameworkServicesAutoloader */
    private $autoloader;

    /** @var PGFrameworkServicesPathfinder */
    private $pathfinder;

    /** @var PGFrameworkContainer */
    private $container;

    private static $required_paths = array(
        'bundles',
        'bundles-resources',
        'bundles-media',
        'module',
        'module-resources',
        'var',
        'media'
    );

    private static $required_constants = array(
        'PAYGREEN_VENDOR_DIR',
        'PAYGREEN_VAR_DIR',
        'PAYGREEN_MEDIA_DIR',
        'PAYGREEN_MODULE_VERSION'
    );

    private static $default_libraries = array(
        'PGFramework:/Functions/development.php',
        'PGFramework:/Functions/templating.php'
    );

    private static $default_created_folders = array(
        'var'
    );

    private static $default_vendors = array(
        'PGModule' => 'PGModule',
        'PGFramework' => 'PGFramework',
        'PGClient' => 'PGClient',
        'PGDomain' => 'PGDomain'
    );

    private static $default_services = array(
        'bundles-resources:/config/services'
    );

    private static $default_parameters = array(
        'bundles-resources:/config/parameters'
    );

    /**
     * PGFrameworkBootstrap constructor.
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
     * @return PGFrameworkContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return PGFrameworkServicesAutoloader
     */
    public function getAutoloader()
    {
        return $this->autoloader;
    }

    /**
     * @return PGFrameworkServicesPathfinder
     */
    public function getPathfinder()
    {
        return $this->pathfinder;
    }

    /**
     * @param array $paths
     * @return self
     * @throws Exception
     */
    public function buildPathfinder(array $paths)
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->verifyPaths($paths);

        require_once $paths['bundles'] . $DS . 'PGFramework' . $DS . 'Services' . $DS . 'Pathfinder.php';

        $paths = $this->getPathfinderConfiguration($paths);

        $this->pathfinder = new PGFrameworkServicesPathfinder($paths);

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

    protected function getPathfinderConfiguration(array $paths)
    {
        $pathfinderConfiguration = array(
            'PGFramework' => $paths['bundles'] . '/PGFramework',
            'PGClient' => $paths['bundles'] . '/PGClient',
            'PGDomain' => $paths['bundles'] . '/PGDomain',
            'PGModule' => $paths['bundles'] . '/PGModule'
        );

        unset($paths['bundles']);

        return array_merge($pathfinderConfiguration, $paths);
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

        $folders = array_merge(
            self::$default_created_folders,
            $additionalFolders
        );

        foreach($folders as $folder) {
            if (strstr($folder, ':') !== false) {
                list($base, $src) = explode(':', $folder, 2);
            } else {
                $base = $folder;
                $src = '';
            }

            $path = $this->pathfinder->toAbsolutePath($base, $src);

            if (!is_dir($path)) {
                mkdir($path, 0770, true);
            }
        }

        return $this;
    }

    /**
     * @param array $additionalLibraries
     * @return self
     * @throws Exception
     */
    public function preloadFunctions(array $additionalLibraries = array())
    {
        if ($this->pathfinder === null) {
            throw new Exception("PathFinder must be initialized before loading functions.");
        }

        $libraries = array_merge(
            self::$default_libraries,
            $additionalLibraries
        );

        foreach($libraries as $library) {
            list($base, $src) = explode(':', $library, 2);

            require_once $this->pathfinder->toAbsolutePath($base, $src);
        }

        return $this;
    }

    /**
     * @param array $additionalVendors
     * @return self
     * @throws Exception
     */
    public function registerAutoloader(array $additionalVendors = array())
    {
        if ($this->pathfinder === null) {
            throw new Exception("PathFinder must be initialized before loading functions.");
        }

        $vendors = array_merge(
            self::$default_vendors,
            $additionalVendors
        );

        require_once $this->pathfinder->toAbsolutePath('PGFramework', '/Services/Autoloader.php');

        $this->autoloader = new PGFrameworkServicesAutoloader();

        foreach ($vendors as $vendor => $basePath) {
            $path = $this->pathfinder->toAbsolutePath($basePath);
            $this->autoloader->addVendor($vendor, $path);
        }

        spl_autoload_register(array($this->autoloader, 'autoload'), true, true);

        return $this;
    }

    /**
     * @param array $additionalServicePaths
     * @param array $additionalParametersPaths
     * @return self
     * @throws Exception
     */
    public function buildContainer(array $additionalServicePaths = array(), array $additionalParametersPaths = array())
    {
        if ($this->pathfinder === null) {
            throw new Exception("PathFinder must be initialized before loading functions.");
        } elseif ($this->autoloader === null) {
            throw new Exception("Autoloader must be initialized before loading functions.");
        }

        $this->container = PGFrameworkContainer::getInstance();

        $this->loadServiceLibrary($additionalServicePaths);
        $this->loadParameters($additionalParametersPaths);

        return $this;
    }

    /**
     * @param array $additionalPaths
     * @throws Exception
     */
    private function loadServiceLibrary(array $additionalPaths)
    {
        $services = array_merge(
            self::$default_services,
            $additionalPaths
        );

        /** @var PGFrameworkComponentsServiceLibrary $library */
        $library = $this->container->get('service.library');

        foreach($services as $service) {
            list($base, $src) = explode(':', $service, 2);

            $path = $this->pathfinder->toAbsolutePath($base, $src);

            $library->addConfigurationFolder($path);
        }
    }

    /**
     * @param array $additionalPaths
     * @throws Exception
     */
    private function loadParameters(array $additionalPaths)
    {
        $parametersPaths = array_merge(
            self::$default_parameters,
            $additionalPaths
        );

        /** @var PGFrameworkComponentsParameters $parameters */
        $parameters = $this->container->get('parameters');

        foreach($parametersPaths as $parameterPath) {
            list($base, $src) = explode(':', $parameterPath, 2);

            $path = $this->pathfinder->toAbsolutePath($base, $src);

            $parameters->addParametersFolder($path);
        }
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
            'autoloader' => $this->autoloader,
            'pathfinder' => $this->pathfinder
        );

        $services = array_merge(
            $defaultServices,
            $additionalServices
        );

        foreach($services as $name => $service) {
            $this->container->set($name, $service);
        }

        return $this;
    }

    /**
     * @return self
     * @throws Exception
     */
    public function setup($flags = PGFrameworkServicesHandlersSetupHandler::ALL)
    {
        if ($this->container === null) {
            throw new Exception("Container must be initialized before running setup.");
        }

        /** @var PGFrameworkServicesHandlersSetupHandler $setupHandler */
        $setupHandler = $this->container->get('handler.setup');

        $setupHandler->run($flags);

        return $this;
    }
}
