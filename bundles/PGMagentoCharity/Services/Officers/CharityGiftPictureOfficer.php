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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGMagentoCharity\Services\Officers;

use Exception;
use Magento\Framework\Exception\LocalizedException as LocalLocalizedException;
use Magento\Framework\Exception\FileSystemException as LocalFileSystemException;
use Magento\Framework\App\Filesystem\DirectoryList as LocalFilesystemDirectoryList;
use Magento\Framework\Filesystem as LocalFilesystem;
use Magento\Catalog\Model\Product as LocalProduct;
use Magento\Catalog\Model\Product\Media\Config as LocalProductMediaConfig;
use PGI\Module\PGFramework\Foundations\AbstractService;
use PGI\Module\PGSystem\Services\Pathfinder;

/**
 * Class CharityGiftPictureOfficer
 * @package PGMagentoCharity\Services\Officers
 */
class CharityGiftPictureOfficer extends AbstractService
{
    /** @var Pathfinder */
    private $pathFinder;

    /** @var LocalProductMediaConfig */
    private $localProductMediaConfig;

    /** @var LocalFilesystem */
    private $localFilesystem;

    public function __construct(
        Pathfinder $pathFinder,
        LocalProductMediaConfig $localProductMediaConfig,
        LocalFilesystem $localFilesystem
    ) {
        $this->pathFinder = $pathFinder;
        $this->localProductMediaConfig = $localProductMediaConfig;
        $this->localFilesystem = $localFilesystem;
    }

    /**
     * @param LocalProduct $localProduct
     * @throws LocalFileSystemException
     * @throws LocalLocalizedException
     */
    public function install(LocalProduct $localProduct)
    {
        $this->log()->debug('Install charity gift image.');

        $path = $this->storePicture();

        $localProduct->addImageToMediaGallery(
            $path,
            array('image', 'small_image', 'thumbnail'),
            false,
            false
        );
    }

    /**
     * @return string
     * @throws LocalFileSystemException
     * @throws Exception
     */
    protected function storePicture(): string
    {
        $sourceFile = $this->pathFinder->toAbsolutePath($this->getConfig('gift_picture'));

        $mediaDirectory = $this->localFilesystem->getDirectoryWrite(
            LocalFilesystemDirectoryList::MEDIA
        );

        $mediaDirectory->create($this->localProductMediaConfig->getBaseTmpMediaPath());

        $targetFile = $this->localProductMediaConfig->getTmpMediaPath(basename($sourceFile));

        copy($sourceFile, $mediaDirectory->getAbsolutePath($targetFile));

        return $targetFile;
    }
}