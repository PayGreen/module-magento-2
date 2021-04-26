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
 * @version   2.0.0
 *
 */

/**
 * Class PGFrameworkServicesDiagnosticsMediaFilesChmod
 * @package PGFramework\Services\Diagnostics
 */
class PGFrameworkServicesDiagnosticsMediaFilesChmod extends PGFrameworkFoundationsAbstractDiagnostic
{
    /** @var PGSystemServicesPathfinder */
    private $pathfinder;

    public function __construct(PGSystemServicesPathfinder $pathfinder)
    {
        $this->pathfinder = $pathfinder;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isValid()
    {
        return (!$this->isFolderExists() || $this->isFilesChmodIsValid());
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function isFolderExists()
    {
        $path = $this->getMediaPath();

        return (file_exists($path) && is_dir($path));
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function isFilesChmodIsValid()
    {
        $this->requirements();

        $folder = $this->getMediaPath();
        $files = scandir($folder);

        $result = true;

        if ($files) {
            foreach ($files as $file) {
                $path = $folder . DIRECTORY_SEPARATOR . $file;

                if (!is_dir($path)) {
                    $chmod = PGFrameworkToolsFile::getChmod($path);

                    if ($chmod !== PGFrameworkServicesHandlersPictureHandler::MEDIA_FILE_CHMOD) {
                        $result = false;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function resolve()
    {
        $this->requirements();

        $folder = $this->getMediaPath();
        $files = scandir($folder);

        $result = true;

        if ($files) {
            foreach ($files as $file) {
                $path = $folder . DIRECTORY_SEPARATOR . $file;

                if (!is_dir($path)) {
                    $chmodFile = PGFrameworkToolsFile::getChmod($path);
                    $chmodConfig = PGFrameworkServicesHandlersPictureHandler::MEDIA_FILE_CHMOD;

                    if (($chmodFile !== $chmodConfig) && !chmod($path, $chmodConfig)) {
                        $result = false;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getMediaPath()
    {
        return $this->pathfinder->toAbsolutePath('media');
    }

    /**
     * @throws Exception
     */
    private function requirements()
    {
        $path = $this->getMediaPath();

        if (!is_dir($path)) {
            throw new Exception("PayGreen media folder not found: $path");
        }
    }
}
