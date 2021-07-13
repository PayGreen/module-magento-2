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
 * @version   2.1.1
 *
 */

namespace Paygreen\Payment\Setup;

use PGModuleServicesHandlersSetup;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

require_once PAYGREEN_BOOTSTRAP_SRC;

class Uninstall extends \PGSystemFoundationsObject implements UninstallInterface
{
    /**
     * @param SchemaSetupInterface $installer
     * @param ModuleContextInterface $context
     * {@inheritdoc}
     */
    public function uninstall(
            SchemaSetupInterface $installer,
            ModuleContextInterface $context)
    {
        $installer->startSetup();

        /** @var PGModuleServicesHandlersSetup $setupHandler */
        $setupHandler = $this->getService('handler.setup');

        $setupHandler->uninstall();

        $installer->endSetup();
    }
}
