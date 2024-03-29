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
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGMagento\Services\Facades;

use Magento\Framework\App\ProductMetadataInterface as LocalProductMetadataInterface;
use PGI\Module\PGModule\Interfaces\ApplicationFacadeInterface;
use PGI\Module\PGSystem\Foundations\AbstractObject;

/**
 * Class ApplicationFacade
 * @package PGMagento\Services\Facades
 */
class ApplicationFacade extends AbstractObject implements ApplicationFacadeInterface
{
    public function getName()
    {
        return 'Magento-2';
    }

    public function getVersion()
    {
        /** @var LocalProductMetadataInterface $productMetadata */
        $productMetadata = $this->getService('magento')->get('Magento\Framework\App\ProductMetadataInterface');

        return $productMetadata->getVersion();
    }
}
