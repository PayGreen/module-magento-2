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
 * @version   0.3.3
 */

/**
 * Class PGDomainExceptionsPaygreenAccountException
 * @package PGDomain\Exceptions
 */
class PGDomainExceptionsPaygreenAccountException extends Exception
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
