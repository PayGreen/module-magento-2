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

namespace PGI\Module\PGForm\Services\Validators;

use PGI\Module\PGForm\Foundations\AbstractValidator;

/**
 * Class LengthMaxValidator
 * @package PGForm\Services\Validators
 */
class LengthMaxValidator extends AbstractValidator
{
    const ERROR_TRANSLATION_KEY = 'errors.validator.max_length';

    protected function test($value)
    {
        return (strlen($value) <= $this->getDefaultConfig('max'));
    }

    protected function getErrorData()
    {
        return array(
            'max' => $this->getDefaultConfig('max')
        );
    }
}
