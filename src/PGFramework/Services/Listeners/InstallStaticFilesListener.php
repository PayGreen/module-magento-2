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

class PGFrameworkServicesListenersInstallStaticFilesListener
{
    /** @var PGFrameworkServicesHandlersStaticFileHandler */
    private $staticFileHandler;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    public function __construct(PGFrameworkServicesHandlersStaticFileHandler $staticFileHandler, PGFrameworkServicesLogger $logger)
    {
        $this->staticFileHandler = $staticFileHandler;
        $this->logger = $logger;
    }

    public function installStaticFiles()
    {
        try {
            if ($this->staticFileHandler->isInstallRequired()) {
                $this->logger->info("Install static files.");
                $this->staticFileHandler->installStaticFiles();
            } else {
                $this->logger->notice("Static file installation is not required.");
            }
        } catch (Exception $exception) {
            $this->logger->error("Error during static file installation : " . $exception->getMessage(), $exception);
        }
    }
}
