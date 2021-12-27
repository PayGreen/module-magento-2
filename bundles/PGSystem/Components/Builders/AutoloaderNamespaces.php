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

namespace PGI\Module\PGSystem\Components\Builders;

use PGI\Module\PGSystem\Components\Bootstrap as BootstrapComponent;
use PGI\Module\PGSystem\Interfaces\Builders\BootstrapBuilderInterface;
use PGI\Module\PGSystem\Interfaces\BundleInterface;
use PGI\Module\PGSystem\Services\Autoloaders\NamespacedAutoloader;
use Exception;

/**
 * Class AutoloaderNamespaces
 * @package PGSystem\Components\Builders
 */
class AutoloaderNamespaces implements BootstrapBuilderInterface
{
    /** @var BootstrapComponent */
    private $bootstrap;

    public function __construct(BootstrapComponent $bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    /**
     * @param array $config
     * @return NamespacedAutoloader
     * @throws Exception
     */
    public function build(array $config = array())
    {
        if ($this->bootstrap->getPathfinder() === null) {
            throw new Exception("Autoloader require Pathfinder.");
        } elseif ($this->bootstrap->getKernel() === null) {
            throw new Exception("Autoloader require Kernel.");
        }

        $autoloader = new NamespacedAutoloader($this->bootstrap->getPathfinder());

        /** @var BundleInterface $bundle */
        foreach ($this->bootstrap->getKernel()->getBundles() as $bundle) {
            $autoloader->addVendor($bundle->getName(), $bundle->getPath());
        }

        return $autoloader;
    }
}
