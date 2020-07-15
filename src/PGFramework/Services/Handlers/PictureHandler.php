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

/**
 * Class PGFrameworkServicesHandlersPictureHandler
 * @package PGFramework\Services\Handlers
 */
class PGFrameworkServicesHandlersPictureHandler extends PGFrameworkFoundationsAbstractObject
{
    /** @var string */
    private $basePath;

    /** @var string */
    private $baseUrl;

    const DEFAULT_PICTURE = 'default-payment-button.png';

    const MEDIA_FOLDER_CHMOD = 0775;
    const MEDIA_FILE_CHMOD = 0664;

    /**
     * PGFrameworkServicesHandlersPictureHandler constructor.
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
     * @param string|null $base
     * @return string
     */
    public function getUrl($filename, $base = null)
    {
        if (!$filename) {
            $filename = self::DEFAULT_PICTURE;
        }

        if (!$this->isStored($filename)) {
            /** @var PGFrameworkServicesLogger $logger */
            $logger = $this->getService('logger');

            $logger->alert("Unknown media file : '$filename'.");
        }

        $url = $this->baseUrl . '/' . $filename;

        if ($base !== null) {
            if ((substr($base, -1, 1) === '/') && (substr($url, 0, 1) === '/')) {
                $url = substr($url, 1);
            } elseif ((substr($base, -1, 1) !== '/') && (substr($url, 0, 1) !== '/')) {
                $url = '/' . $url;
            }

            $url = $base . $url;
        }

        return $url;
    }

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return string
     */
    public function getButtonFinalUrl(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        $filename = $button->getImageSrc();

        if (!empty($filename) && !$this->isStored($filename)) {
            $filename = self::DEFAULT_PICTURE;
        }

        return $this->getUrl($filename);
    }

    /**
     * @param string $source
     * @param string $name
     * @param bool $keepOriginalName
     * @return PGFrameworkEntitiesPicture
     * @throws Exception
     */
    public function store($source, $name, $keepOriginalName = false)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        if (!is_file($source)) {
            throw new Exception("Source file not found : '$source'.");
        }

        if (!$keepOriginalName) {
            $name = $this->getHashFilename($source, $name);
        }

        $path = $this->getPath($name);

        if (is_file($path)) {
            $logger->warning("Target file already stored : '$path'.");
        } elseif (is_writable($source)) {
            rename($source, $path);
        } else {
            copy($source, $path);
        }

        chmod($path, self::MEDIA_FILE_CHMOD);

        return $this->getPicture($name);
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
     * @return PGFrameworkEntitiesPicture
     * @throws Exception
     */
    public function getPicture($filename)
    {
        if (!$this->isStored($filename)) {
            throw new Exception("Unknown media file : '$filename'.");
        }

        return new PGFrameworkEntitiesPicture($this->getPath($filename));
    }
}
