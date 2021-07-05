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
 * Class PGModuleServicesListenersInstallDefaultSettings
 * @package PGModule\Services\Listeners
 */
class PGModuleServicesListenersInstallDefaultSettings
{
    /** @var PGModuleServicesSettings */
    private $settings;

    /** @var PGModuleServicesLogger */
    private $logger;

    private $bin;

    public function __construct(
        PGModuleServicesSettings $settings,
        PGModuleServicesLogger $logger
    ) {
        $this->settings = $settings;
        $this->logger = $logger;
    }

    public function listen(PGModuleComponentsEventsModule $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;

        $this->logger->debug("Installing default settings.");

        try {
            $this->settings->installDefault();

            $this->logger->info("Default settings installed successfully.");
        } catch (Exception $exception) {
            $this->logger->error(
                "An error occurred during default settings install : " . $exception->getMessage(),
                $exception
            );
        }
    }
}
