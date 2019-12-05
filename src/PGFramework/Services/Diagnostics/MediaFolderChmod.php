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
 * @version   0.3.4
 */

class PGFrameworkServicesDiagnosticsMediaFolderChmod extends PGFrameworkFoundationsAbstractDiagnostic
{
    /** @var PGFrameworkServicesPathfinder */
    private $pathfinder;

    public function __construct(PGFrameworkServicesPathfinder $pathfinder)
    {
        $this->pathfinder = $pathfinder;
    }

    public function isValid()
    {
        return (!$this->isFolderExists() || $this->isFolderChmodIsValid());
    }

    protected function isFolderExists()
    {
        $mediaPath = $this->getMediaPath();

        return (file_exists($mediaPath) && is_dir($mediaPath));
    }

    protected function isFolderChmodIsValid()
    {
        $this->requirements();

        $chmod = octdec(substr(sprintf('%o', fileperms($this->getMediaPath())), -4));

        return ($chmod === PGFrameworkServicesHandlersPictureHandler::MEDIA_FOLDER_CHMOD);
    }

    public function resolve()
    {
        $this->requirements();

        $result = chmod(
            $this->getMediaPath(),
            PGFrameworkServicesHandlersPictureHandler::MEDIA_FOLDER_CHMOD
        );

        if (!$result) {
            throw new Exception("Unable to resolve media folder chmod issue.");
        }
    }

    protected function getMediaPath()
    {
        return $this->pathfinder->toAbsolutePath('media');
    }

    private function requirements()
    {
        $mediaPath = $this->getMediaPath();

        if (!is_dir($mediaPath)) {
            throw new Exception("Paygreen media folder not found.");
        }
    }
}