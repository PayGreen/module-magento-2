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
 */

namespace Paygreen\Payment\Setup;

use PGFrameworkServicesHandlersSetupHandler;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

require_once PAYGREEN_BOOTSTRAP_SRC;

class InstallSchema extends \PGFrameworkFoundationsAbstractObject implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $installer
     * @param ModuleContextInterface $context
     * {@inheritdoc}
     */
    public function install(
            SchemaSetupInterface $installer,
            ModuleContextInterface $context)
    {

        $installer->startSetup();

        /** @var PGFrameworkServicesHandlersSetupHandler $setupHandler */
        $setupHandler = $this->getService('handler.setup');

        $setupHandler->install();

        $installer->endSetup();
    }
}
