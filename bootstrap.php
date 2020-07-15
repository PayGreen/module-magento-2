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

// #############################################################################################
// Setting module constants
// #############################################################################################

try {
    if (!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
    }

    define('PAYGREEN_MODULE_VERSION', '1.0.0');

    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

    /** @var \Magento\Framework\Filesystem\DirectoryList $directory */
    $directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');

    define('PAYGREEN_VENDOR_DIR', PAYGREEN_MODULE_DIR . DS . 'src');
    define('PAYGREEN_VAR_DIR', $directory->getPath('var') . DS . 'paygreen');
    define('PAYGREEN_MEDIA_DIR', $directory->getPath('media') . DS . 'paygreen');
    define('PAYGREEN_CONFIG_DIR', $directory->getPath('var') . DS . 'paygreen');

// #############################################################################################
// Running Bootstrap
// #############################################################################################

    require_once PAYGREEN_VENDOR_DIR . DS . 'PGFramework' . DS . 'Bootstrap.php';

    $bootstrap = new PGFrameworkBootstrap(PAYGREEN_VENDOR_DIR);

    $bootstrap
        ->buildAppliance('Paygreen payment module for Prestashop')
        ->addVendors(array())
        ->buildPathfinder(array(
            'static' => PAYGREEN_MODULE_DIR . '/view/base/web/static',
            'module' => PAYGREEN_MODULE_DIR,
            'var' => PAYGREEN_VAR_DIR,
            'log' => PAYGREEN_VAR_DIR . '/logs',
            'cache' => PAYGREEN_VAR_DIR . '/cache',
            'media' => PAYGREEN_MEDIA_DIR,
            'config' => PAYGREEN_CONFIG_DIR
        ))
        ->preloadFunctions()
        ->createVarFolder()
        ->registerAutoloader()
        ->buildContainer()
        ->insertStaticServices(array(
            'magento' => $objectManager
        ))
        ->setup(PGFrameworkServicesHandlersSetupHandler::UPGRADE)
    ;

    // #############################################################################################
    // Logging End of bootstrap
    // #############################################################################################

    /** @var PGFrameworkServicesLogger $logger */
    $logger = $bootstrap->getContainer()->get('logger');

    /** @var PGDomainInterfacesShopHandlerInterface $shopHandler */
    $shopHandler = $bootstrap->getContainer()->get('handler.shop');

    /** @var PGDomainInterfacesEntitiesShopInterface $shop */
    $shop = $shopHandler->getCurrentShop();

    $logger->debug("Current shop detected : {$shop->getName()} #{$shop->id()}.");

    if (isset($_SERVER) && is_array($_SERVER) && isset($_SERVER['REQUEST_URI'])) {
        $logger->debug("Paygreen bootstrap successfully executed for URI : {$_SERVER['REQUEST_URI']}");
    } else {
        $logger->notice("Paygreen bootstrap successfully executed in non-HTTP context.");
    }

    // #############################################################################################
    // Logging PHP errors
    // #############################################################################################

    if (PAYGREEN_ENV === 'DEV') {
        ini_set('error_log', PAYGREEN_VAR_DIR . DS . 'error.log');
    }
} catch (Exception $exception) {
    die($exception->getMessage());
}
