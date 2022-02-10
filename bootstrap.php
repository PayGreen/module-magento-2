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
 * @version   2.5.2
 *
 */

use Magento\Framework\App\ObjectManager as LocalObjectManager;
use Magento\Framework\Filesystem\DirectoryList as LocalDirectoryList;
use PGI\Module\PGModule\Services\Handlers\SetupHandler;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGShop\Interfaces\Entities\ShopEntityInterface;
use PGI\Module\PGShop\Services\Handlers\ShopHandler;
use PGI\Module\PGSystem\Components\Bootstrap as BootstrapComponent;

// #############################################################################################
// Setting module constants
// #############################################################################################

try {
    if (!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
    }

    $objectManager = LocalObjectManager::getInstance();

    /** @var LocalDirectoryList $directory */
    $directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');

    define('PAYGREEN_MODULE_NAME', 'paygreen');
    define('PAYGREEN_VENDOR_DIR', PAYGREEN_MODULE_DIR . DS . 'bundles');
    define('PAYGREEN_VAR_DIR', $directory->getPath('var') . DS . PAYGREEN_MODULE_NAME);
    define('PAYGREEN_MEDIA_DIR', $directory->getPath('media') . DS . PAYGREEN_MODULE_NAME);
    define('PAYGREEN_DATA_DIR', PAYGREEN_MODULE_DIR . DS . 'data');

    define('PAYGREEN_MODULE_VERSION', require(PAYGREEN_DATA_DIR . DS . 'module_version.php'));

    require_once(PAYGREEN_MODULE_DIR . DS . 'includer.php');

// #############################################################################################
// Running Bootstrap
// #############################################################################################

    $bootstrap = new BootstrapComponent();

    $bootstrap
        ->buildKernel(PAYGREEN_DATA_DIR)
        ->buildPathfinder(array(
            'static' => PAYGREEN_MODULE_DIR . '/view/base/web/static',
            'module' => PAYGREEN_MODULE_DIR,
            'data' => PAYGREEN_MODULE_DIR . '/data',
            'var' => PAYGREEN_VAR_DIR,
            'log' => PAYGREEN_VAR_DIR . '/logs',
            'cache' => PAYGREEN_VAR_DIR . '/cache',
            'media' => PAYGREEN_MEDIA_DIR,
            'templates' => PAYGREEN_MODULE_DIR . '/templates'
        ))
        ->createVarFolder()
    ;

    if (PAYGREEN_AUTOLOADING) {
        $bootstrap->registerAutoloader('PGI\Module\PGSystem\Components\Builders\AutoloaderCompiled');
    }

    $bootstrap
        ->buildContainer()
        ->insertStaticServices(array(
            'magento' => $objectManager
        ))
    ;

    /** @var ShopHandler $shopHandler */
    $shopHandler = $bootstrap->getContainer()->get('handler.shop');

    /** @var ShopEntityInterface $shop */
    $shop = $shopHandler->getCurrentShop();

    if ($shopHandler->isMultiShopActivated()) {
        define('PAYGREEN_CACHE_PREFIX', 'shop-' . $shop->id());
    }

    $bootstrap->setup(SetupHandler::UPGRADE);

    // #############################################################################################
    // Logging End of bootstrap
    // #############################################################################################

    /** @var Logger $logger */
    $logger = $bootstrap->getContainer()->get('logger');

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