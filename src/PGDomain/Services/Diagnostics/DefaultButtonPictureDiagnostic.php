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
 * @version   1.1.0
 */

class PGDomainServicesDiagnosticsDefaultButtonPictureDiagnostic extends PGFrameworkFoundationsAbstractDiagnostic
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

    public function installDefaultButtonPicture()
    {
        if (!$this->isValid()) {
            try {
                $this->resolve();
            } catch (Exception $exception) {
                $this->logger->error("Error during default button picture installation : " . $exception->getMessage(), $exception);
            }
        }
    }

    public function isValid()
    {
        $defaultButtonFilename = PGFrameworkServicesHandlersPictureHandler::DEFAULT_PICTURE;

        return $this->mediaHandler->isStored($defaultButtonFilename);
    }

    /**
     * @throws Exception
     */
    public function resolve()
    {
        $defaultButtonFilename = PGFrameworkServicesHandlersPictureHandler::DEFAULT_PICTURE;
        $defaultButtonSrc = $this->pathfinder->toAbsolutePath('static', "/pictures/PGDomain/$defaultButtonFilename");

        if (!is_file($defaultButtonSrc)) {
            throw new Exception("Default button picture not found : '$defaultButtonSrc'.");
        }

        $result = false;

        try {
            $this->mediaHandler->store($defaultButtonSrc, $defaultButtonFilename, true);

            $this->logger->info("Default button image successfully installed.");

            $result = true;
        } catch (Exception $exception) {
            $this->logger->error("An error occured during default button installation : " . $exception->getMessage(), $exception);
        }

        return $result;
    }
}
