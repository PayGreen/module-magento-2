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

use Magento\Framework\Component\ComponentRegistrar as LocalComponentRegistrar;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('PAYGREEN_MODULE_DIR', BP . DS . 'app' . DS . 'code' . DS . 'Paygreen' . DS . 'Payment');
define('PAYGREEN_BOOTSTRAP_SRC', PAYGREEN_MODULE_DIR . DS . 'bootstrap.php');
define('PAYGREEN_AUTOLOADING', true);

LocalComponentRegistrar::register(
    LocalComponentRegistrar::MODULE,
    'Paygreen_Payment',
    PAYGREEN_MODULE_DIR
);
