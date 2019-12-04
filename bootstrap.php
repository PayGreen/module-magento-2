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
 * @version   0.3.2
 */

// #############################################################################################
// Setting module constants
// #############################################################################################

try {
    if (!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
    }

    define('PAYGREEN_MODULE_VERSION', '0.3.2');

    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

    $directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');

    define('PAYGREEN_VENDOR_DIR', PAYGREEN_MODULE_DIR . DS . 'src');
    define('PAYGREEN_VAR_DIR', $directory->getPath('var') . DS . 'paygreen');
    define('PAYGREEN_MEDIA_DIR', $directory->getPath('media') . DS . 'paygreen');

// #############################################################################################
// Running Bootstrap
// #############################################################################################

    require_once PAYGREEN_VENDOR_DIR . DS . 'PGFramework' . DS . 'Bootstrap.php';

    $bootstrap = new PGFrameworkBootstrap();

    $bootstrap
        ->buildPathfinder(array(
            'bundles' => PAYGREEN_VENDOR_DIR,
            'bundles-resources' => PAYGREEN_VENDOR_DIR . '/resources',
            'bundles-media' => PAYGREEN_MODULE_DIR . '/resources/media',
            'module' => PAYGREEN_MODULE_DIR,
            'module-resources' => PAYGREEN_MODULE_DIR . '/resources',
            'var' => PAYGREEN_VAR_DIR,
            'media' => PAYGREEN_MEDIA_DIR
        ))
        ->preloadFunctions()
        ->createVarFolder()
        ->registerAutoloader()
        ->buildContainer(array(
            'module-resources:/config/services'
        ), array(
            'module-resources:/config/parameters'
        ))
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

    $logger->notice("Paygreen bootstrap successfully executed.");

// #############################################################################################
// Logging PHP errors
// #############################################################################################

    if (PAYGREEN_ENV === 'DEV') {
        ini_set('error_log', PAYGREEN_VAR_DIR . DS . 'error.log');
    }
} catch (Exception $exception) {
    die($exception->getMessage());
}
