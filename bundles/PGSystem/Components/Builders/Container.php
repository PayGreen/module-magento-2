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
 * @version   2.1.0
 *
 */

/**
 * Class PGSystemComponentsBuildersContainer
 * @package PGSystem\Components\Builders
 */
class PGSystemComponentsBuildersContainer
{
    /** @var PGSystemServicesPathfinder */
    private $pathfinder;

    public function __construct(PGSystemServicesPathfinder $pathfinder)
    {
        $this->pathfinder = $pathfinder;
    }

    /**
     * @return PGSystemServicesContainer
     * @throws Exception
     */
    public function buildContainer()
    {
        $container = PGSystemServicesContainer::getInstance();

        $this->loadServiceLibrary($container);
        $this->loadParameters($container);

        return $container;
    }

    /**
     * @param PGSystemServicesContainer $container
     * @throws Exception
     */
    private function loadServiceLibrary(PGSystemServicesContainer $container)
    {
        /** @var PGSystemComponentsServiceLibrary $library */
        $library = $container->get('service.library');

        if (defined('PAYGREEN_SUBSET')) {
            $filename = 'container-' . PAYGREEN_SUBSET . '.php';
        } else {
            $filename = 'container.php';
        }

        $path = $this->pathfinder->toAbsolutePath("data:/$filename");

        $library->setSource($path)->reset();
    }

    /**
     * @param PGSystemServicesContainer $container
     * @throws Exception
     */
    private function loadParameters(PGSystemServicesContainer $container)
    {
        /** @var PGSystemComponentsParameters $parameters */
        $parameters = $container->get('parameters');

        if (defined('PAYGREEN_SUBSET')) {
            $filename = 'parameters-' . PAYGREEN_SUBSET . '.php';
        } else {
            $filename = 'parameters.php';
        }

        $path = $this->pathfinder->toAbsolutePath("data:/$filename");

        $parameters->setSource($path)->reset();
    }
}
