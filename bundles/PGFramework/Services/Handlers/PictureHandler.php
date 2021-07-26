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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGFramework\Services\Handlers;

use PGI\Module\PGFramework\Components\Picture as PictureComponent;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class PictureHandler
 * @package PGFramework\Services\Handlers
 */
class PictureHandler extends AbstractObject
{
    /** @var string */
    private $basePath;

    /** @var string */
    private $baseUrl;

    const MEDIA_FOLDER_CHMOD = 0775;
    const MEDIA_FILE_CHMOD = 0664;

    /**
     * PictureHandler constructor.
     * @param string $basePath
     * @param string $baseUrl
     */
    public function __construct($basePath, $baseUrl)
    {
        if (!is_dir($basePath)) {
            mkdir($basePath, self::MEDIA_FOLDER_CHMOD, true);
        }

        $this->basePath = realpath($basePath);
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $filename
     * @return string
     */
    public function getPath($filename)
    {
        return $this->basePath . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * @param string $filename
     * @return string
     * @throws Exception
     */
    public function getUrl($filename)
    {
        if (!$filename) {
            throw new Exception("A filename must be provided.");
        }

        if (!$this->isStored($filename)) {
            /** @var Logger $logger */
            $logger = $this->getService('logger');

            $logger->alert("Unknown media file : '$filename'.");
        }

        return $this->baseUrl . '/' . $filename;
    }

    /**
     * @param string $source
     * @param string $name
     * @param bool $keepOriginal
     * @return PictureComponent
     * @throws Exception
     */
    public function store($source, $name, $keepOriginal = false)
    {
        /** @var Logger $logger */
        $logger = $this->getService('logger');

        if (!is_file($source)) {
            throw new Exception("Source file not found : '$source'.");
        }

        if (!$keepOriginal) {
            $name = $this->getHashFilename($source, $name);
        }

        $path = $this->getPath($name);

        if (file_exists($path)) {
            $logger->warning("Target file already stored : '$path'.");
        } elseif (is_writable($source) && !$keepOriginal) {
            rename($source, $path);
        } else {
            copy($source, $path);
        }

        chmod($path, self::MEDIA_FILE_CHMOD);

        return $this->getPicture($name);
    }

    /**
     * @param string $filename
     * @throws Exception
     * @return bool
     */
    public function removeFromStore($filename)
    {
        /** @var Logger $logger */
        $logger = $this->getService('logger');

        if (!$this->isStored($filename)) {
            throw new Exception("Unknown media file : '$filename'.");
        }

        $path = $this->getPath($filename);

        if (unlink($path)) {
            $logger->debug("File '$path' removed successfully.");
            return true;
        } else {
            throw new Exception("Something went wrong when trying to remove '$path'.");
        }

        return false;
    }

    protected function getHashFilename($source, $name)
    {
        $hash = md5_file($source);
        $ext = pathinfo($name, PATHINFO_EXTENSION);

        return $hash . '.' . $ext;
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function isStored($filename)
    {
        return is_file($this->getPath($filename));
    }

    /**
     * @param string $filename
     * @return PictureComponent
     * @throws Exception
     */
    public function getPicture($filename)
    {
        if (!$this->isStored($filename)) {
            throw new Exception("Unknown media file : '$filename'.");
        }

        return new PictureComponent($this->getPath($filename));
    }
}
