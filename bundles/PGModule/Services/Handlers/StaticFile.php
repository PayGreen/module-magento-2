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
 * @version   2.0.1
 *
 */

/**
 * Class PGModuleServicesHandlersStaticFile
 * @package PGModule\Services\Handlers
 */
class PGModuleServicesHandlersStaticFile extends PGSystemFoundationsObject
{
    /** @var PGSystemComponentsBag */
    protected $config;

    /** @var PGModuleServicesLogger */
    protected $logger;

    /** @var PGSystemServicesPathfinder */
    protected $pathfinder;

    /**
     * PGModuleServicesHandlersStaticFile constructor.
     * @param PGModuleServicesLogger $logger
     * @param PGSystemServicesPathfinder $pathfinder
     * @param array $config
     * @throws Exception
     */
    public function __construct(
        PGModuleServicesLogger $logger,
        PGSystemServicesPathfinder $pathfinder,
        array $config
    ) {
        $this->logger = $logger;
        $this->pathfinder = $pathfinder;
        $this->config = new PGSystemComponentsBag($config);
    }

    /**
     * @return bool
     */
    public function isInstallRequired()
    {
        $isInstallationActivated = (bool) $this->config['install.target'];

        if (is_array($this->config['install.envs']) && (in_array(PAYGREEN_ENV, $this->config['install.envs']))) {
            $isValidEnvironment = true;
        } else {
            $isValidEnvironment = false;
        }

        return ($isInstallationActivated && $isValidEnvironment);
    }

    /**
     * @param string $filename
     * @return string
     */
    public function getUrl($filename)
    {
        $swap = $this->config['swap'];

        if (!empty($swap)) {
            foreach ($swap as $from => $to) {
                if (preg_match("/^\/$from\//", $filename)) {
                    $filename = preg_replace("/^\/$from\//", "/$to/", $filename);
                }
            }
        }

        return $this->config['public'] . $filename;
    }

    /**
     * @throws Exception
     */
    public function installStaticFiles()
    {
        if ($this->isInstallRequired()) {
            $target = $this->pathfinder->toAbsolutePath($this->config['install.target']);
            $from = $this->pathfinder->toAbsolutePath($this->config['path']);

            $this->logger->notice("Install static files in target folder '$target'.");

            if (symlink($from, $target)) {
                $this->logger->info("Static files successfully installed with symlink.");
            } else {
                $this->logger->warning(
                    "Unable to install static files with symlink. Attempt to install them by copy."
                );
                $this->recursiveCopy($from, $target);
                $this->logger->info("Static files successfully installed by copy.");
            }
        } else {
            throw new Exception("Install static files is not required.");
        }
    }

    /**
     * @param string $source
     * @param string $target
     * @throws Exception
     */
    public function recursiveCopy($source, $target)
    {
        $dir = opendir($source);

        if ($dir === false) {
            throw new Exception("Unable to open source folder '$source'.");
        }

        if (!is_dir($target)) {
            $this->logger->notice("Create target folder '$target'.");
            mkdir($target, 0755, true);
        }

        while (false !== ($file = readdir($dir))) {
            if (!in_array($file, array('.', '..'))) {
                if (is_dir("$source/$file")) {
                    $this->recursiveCopy("$source/$file", "$target/$file");
                } else {
                    if (file_exists("$target/$file")) {
                        unlink("$target/$file");
                    }

                    $result = copy("$source/$file", "$target/$file");

                    if ($result === false) {
                        throw new Exception("Unable to copy file '$source/$file' to '$target/$file'.");
                    }
                }
            }
        }

        closedir($dir);
    }
}
