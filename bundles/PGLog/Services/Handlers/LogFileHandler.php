<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGLog\Services\Handlers;

use Exception;
use ZipArchive;
use DateTime;
use PGI\Module\PGFramework\Components\Aggregator;
use PGI\Module\PGFramework\Foundations\AbstractService;
use PGI\Module\PGLog\Interfaces\LogWriterFileInterface;
use PGI\Module\PGSystem\Services\Pathfinder;

class LogFileHandler extends AbstractService
{
    const ARCHIVE_FOLDER_CHMOD = 0775;

    /** @var Aggregator */
    private $aggregator;

    /** @var Pathfinder */
    private $pathfinder;

    public function __construct(
        Aggregator $aggregator,
        Pathfinder $pathfinder
    ) {
        $this->aggregator = $aggregator;
        $this->pathfinder = $pathfinder;
    }

    /**
     * @throws Exception
     */
    public function zip()
    {
        $this->verifyArchiveFolderConfiguration();

        /** @var LogWriterFileInterface $logWriterFile */
        foreach ($this->aggregator as $logWriterName => $logWriterFile) {
            $this->log()->debug("Zipping logWriterFile : '$logWriterName'.");

            $logFile = $logWriterFile->getFilePath();
            if (file_exists($logFile)) {
                $size = filesize($logFile);
                $this->log()->debug("Found log file : $logFile ($size o)");

                if ($size > $this->getConfig('max_size')) {
                    $zipFile = $this->buildLogFilePath($logFile);

                    if (!file_exists($zipFile)) {
                        if ($this->archiveLogFile($logFile, $zipFile)) {
                            $logWriterFile->reset();
                        } else {
                            $this->log()->warning("Unable to create zip archive for this file : $zipFile");
                        }
                    } else {
                        $this->log()->warning("Log archive already exists : $zipFile");
                    }
                } else {
                    $this->log()->debug("Log file to small to be archived.");
                }
            } else {
                $this->log()->warning("Log file not found : $logFile");
            }
        }
    }

    /**
     * @param string $logFile
     * @return string
     * @throws Exception
     */
    protected function buildLogFilePath($logFile)
    {
        $name = pathinfo($logFile,  PATHINFO_FILENAME);
        $dt = new DateTime();

        $zipFile = $this->getConfig('folder') . '/' . $this->getConfig('file');

        $zipFile = str_replace('<name>', $name, $zipFile);
        $zipFile = str_replace('<date>', $dt->format('Y-m-d'), $zipFile);
        $zipFile = str_replace('<time>', $dt->format('H:i:s'), $zipFile);

        return $this->pathfinder->toAbsolutePath($zipFile);
    }

    /**
     * @param string $logFile
     * @param string $zipFile
     * @return bool
     */
    protected function archiveLogFile($logFile, $zipFile)
    {
        $zip = new ZipArchive();

        if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
            $zip->addFile($logFile, basename($logFile));
            $zip->close();

            $size = filesize($zipFile);

            $this->log()->notice("Successfully create archive log file : $zipFile ($size o)");
            $this->log()->debug("Delete old log file : $logFile");

            unlink($logFile);

            return true;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function clean()
    {
        $this->verifyArchiveFolderConfiguration();

        /** @var LogWriterFileInterface $logWriterFile */
        foreach ($this->aggregator as $name => $logWriterFile) {
            $this->log()->debug("Cleaning old archive files for target LogWriterFile : '$name'.");

            $code = pathinfo($logWriterFile->getFilePath(),  PATHINFO_FILENAME);
            $filenames = $this->getArchivesByName($code);

            if (count($filenames) > 10) {
                $this->log()->info("More than 10 zip files found for target LogWriterFile : '$name'.");

                foreach(array_slice($filenames, 10) as $filename) {
                    $path = $this->pathfinder->toAbsolutePath($this->getConfig('folder') . '/' . $filename);
                    $this->log()->notice("Deleting old zip file : $filename");
                    unlink ($path);
                }
            } else {
                $this->log()->debug("Less than 10 archived files. Nothing to delete.");
            }
        }
    }

    /**
     * @throws Exception
     */
    protected function getArchivesByName($name)
    {
        $path = $this->pathfinder->toAbsolutePath($this->getConfig('folder'));

        $filenames = array();

        foreach(scandir($path, SCANDIR_SORT_DESCENDING) as $filename) {
            if (preg_match("/^{$name}_/", $filename)) {
                $filenames[] = $filename;
            }
        }

        return $filenames;
    }

    /**
     * @throws Exception
     */
    protected function verifyArchiveFolderConfiguration()
    {
        $path = $this->pathfinder->toAbsolutePath($this->getConfig('folder'));

        if (!is_dir($path)) {
            mkdir($path, self::ARCHIVE_FOLDER_CHMOD, true);
        }

        if (!is_writable($path)) {
            throw new Exception("Archive folder is not writable.");
        }
    }
}