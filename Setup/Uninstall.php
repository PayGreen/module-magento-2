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

namespace Paygreen\Payment\Setup;

use Magento\Framework\Setup\UninstallInterface as LocalUninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface as LocalSchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface as LocalModuleContextInterface;
use PGI\Module\PGModule\Services\Handlers\SetupHandler;
use PGI\Module\PGSystem\Foundations\AbstractObject;

require_once PAYGREEN_BOOTSTRAP_SRC;

class Uninstall extends AbstractObject implements LocalUninstallInterface
{
    /**
     * @param LocalSchemaSetupInterface $installer
     * @param LocalModuleContextInterface $context
     * {@inheritdoc}
     */
    public function uninstall(
        LocalSchemaSetupInterface $installer,
        LocalModuleContextInterface $context)
    {
        $installer->startSetup();

        /** @var SetupHandler $setupHandler */
        $setupHandler = $this->getService('handler.setup');

        $setupHandler->uninstall();

        $installer->endSetup();
    }
}
