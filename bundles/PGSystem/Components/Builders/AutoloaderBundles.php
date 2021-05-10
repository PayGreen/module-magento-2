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
 * @version   2.0.2
 *
 */

/**
 * Class PGSystemComponentsBuildersAutoloaderBundles
 * @package PGSystem\Components\Builders
 */
class PGSystemComponentsBuildersAutoloaderBundles implements PGSystemInterfacesBootstrapBuilder
{
    /** @var PGSystemBootstrap */
    private $bootstrap;

    public function __construct(PGSystemBootstrap $bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    /**
     * @param array $config
     * @return PGSystemServicesAutoloader
     * @throws Exception
     */
    public function build(array $config = array())
    {
        if ($this->bootstrap->getPathfinder() === null) {
            throw new Exception("Autoloader require Pathfinder.");
        } elseif ($this->bootstrap->getKernel() === null) {
            throw new Exception("Autoloader require Kernel.");
        }

        $varFolder = $this->bootstrap->getPathfinder()->toAbsolutePath('var');

        if (is_dir($varFolder) && is_writable($varFolder)) {
            $filename = $this->bootstrap->getPathfinder()->toAbsolutePath('var', '/autoload.cache.json');
            $storage = new PGSystemComponentsStoragesJSONFile($filename);
        } else {
            $storage = new PGSystemComponentsStoragesBlackHole();
        }

        $autoloader = new PGSystemServicesAutoloader($storage, $this->bootstrap->getPathfinder());

        /** @var PGSystemInterfacesBundle $bundle */
        foreach ($this->bootstrap->getKernel()->getBundles() as $bundle) {
            $autoloader->addVendor($bundle->getName(), $bundle->getPath());
        }

        return $autoloader;
    }
}
