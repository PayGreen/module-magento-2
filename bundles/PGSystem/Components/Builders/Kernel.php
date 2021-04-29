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
 * Class PGSystemComponentsBuilderKernel
 * @package PGSystem\Components\Builders
 */
class PGSystemComponentsBuilderKernel
{
    const DEFAULT_BUNDLE_CLASS_NAME = 'PGSystemComponentsBundle';

    /**
     * @param string $filename
     * @return PGSystemComponentsKernel
     * @throws Exception
     */
    public function buildKernel($filename)
    {
        $kernel = new PGSystemComponentsKernel();

        if (!file_exists($filename)) {
            throw new Exception("Bundle file '$filename' not found.");
        }

        $bundleNames = require($filename);

        foreach ($bundleNames as $bundleName) {
            /** @var PGSystemInterfacesBundle $bundle */
            $bundle = $this->buildBundle($bundleName);

            if ($bundle->isActivated()) {
                $kernel->addBundle($bundle);
            }
        }

        return $kernel;
    }

    /**
     * @param string $bundleName
     * @return PGSystemInterfacesBundle
     */
    private function buildBundle($bundleName)
    {
        $className = $bundleName . 'Bundle';
        if (!class_exists($className, false)) {
            $className = self::DEFAULT_BUNDLE_CLASS_NAME;
        }

        /** @var PGSystemInterfacesBundle $bundle */
        $bundle = new $className($bundleName);

        return $bundle;
    }
}