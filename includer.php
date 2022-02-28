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
 * @version   2.6.0
 *
 */

require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Interfaces' . DS . 'Builders' . DS . 'BootstrapBuilderInterface.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Interfaces' . DS . 'BundleInterface.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Interfaces' . DS . 'StorageInterface.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Components' . DS . 'Bootstrap.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Components' . DS . 'Builders' . DS . 'Kernel.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Components' . DS . 'Builders' . DS . 'Container.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Components' . DS . 'Builders' . DS . 'AutoloaderBundles.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Components' . DS . 'Builders' . DS . 'AutoloaderCompiled.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Components' . DS . 'Builders' . DS . 'AutoloaderNamespaces.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Components' . DS . 'Bundle.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Components' . DS . 'Kernel.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Services' . DS . 'Pathfinder.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Foundations' . DS . 'AbstractStorage.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Foundations' . DS . 'AbstractStorageFile.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Components' . DS . 'Storages' . DS . 'JSONFile.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Components' . DS . 'Storages' . DS . 'BlackHole.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Services' . DS . 'Autoloaders' . DS . 'CamelifiedAutoloader.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Services' . DS . 'Autoloaders' . DS . 'CompiledAutoloader.php');
require_once(PAYGREEN_VENDOR_DIR . DS . 'PGSystem' . DS . 'Services' . DS . 'Autoloaders' . DS . 'NamespacedAutoloader.php');
