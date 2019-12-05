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
 * @version   0.3.5
 */


class PGDomainServicesListenersInstallDefaultButtonImageListener
{
    /** @var PGFrameworkServicesPathfinder */
    private $pathfinder;

    /** @var PGFrameworkServicesHandlersPictureHandler */
    private $mediaHandler;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    public function __construct(PGFrameworkServicesPathfinder $pathfinder, PGFrameworkServicesHandlersPictureHandler $mediaHandler, PGFrameworkServicesLogger $logger)
    {
        $this->pathfinder = $pathfinder;
        $this->mediaHandler = $mediaHandler;
        $this->logger = $logger;
    }

    public function listen(PGFrameworkComponentsEventsModuleEvent $event)
    {
        $defaultButtonFilename = PGFrameworkServicesHandlersPictureHandler::DEFAULT_PICTURE;
        $defaultButtonSrc = $this->pathfinder->toAbsolutePath('bundles-media', "/$defaultButtonFilename");

        if (is_file($defaultButtonSrc) and !$this->mediaHandler->isStored($defaultButtonFilename)) {
            $this->mediaHandler->store($defaultButtonSrc, $defaultButtonFilename);

            $this->logger->info("Default button image successfully installed.");
        }
    }
}