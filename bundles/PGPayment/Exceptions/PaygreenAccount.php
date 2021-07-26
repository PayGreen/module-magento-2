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

namespace PGI\Module\PGPayment\Exceptions;

use Exception;
use ReflectionClass;

/**
 * Class PaygreenAccount
 * @package PGPayment\Exceptions
 */
class PaygreenAccount extends Exception
{
    const ACCOUNT_NOT_FOUND = 1;
    const INVALID_PUBLIC_KEY = 2;
    const INVALID_PRIVATE_KEY = 3;
    const EMPTY_BANK_DATA = 4;
    const EMPTY_SHOP_DATA = 5;

    public function getCodeName()
    {
        $reflexion = new ReflectionClass($this);

        $constantNames = array_flip($reflexion->getConstants());

        return $constantNames[$this->getCode()];
    }
}
