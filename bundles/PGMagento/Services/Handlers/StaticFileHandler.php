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
 * @version   2.0.2
 *
 */

use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Asset\Repository;

/**
 * Class PGMagentoServicesHandlersStaticFileHandler
 * @package PGMagento\Services\Handlers
 */
class PGMagentoServicesHandlersStaticFileHandler extends PGModuleServicesHandlersStaticFile
{
    /** @var Repository */
    private $assetRepository;

    public function setAssetRepository(ObjectManager $objectManager)
    {
        $this->assetRepository = $objectManager->get('Magento\Framework\View\Asset\Repository');
    }

    /**
     * @param string $filename
     * @return string
     */
    public function getUrl($filename)
    {
        $separator = Repository::FILE_ID_SEPARATOR;
        $module = $this->config['module'];

        return $this->assetRepository->getUrl($module . $separator . $this->config['folder'] . $filename);
    }
}
