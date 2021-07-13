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
 * @version   2.1.1
 *
 */

/**
 * Class PGFormServicesValidatorsRangeValidator
 * @package PGForm\Services\Validators
 */
class PGFormServicesValidatorsRangeValidator extends PGFormFoundationsAbstractValidator
{
    const ERROR_TRANSLATION_KEY = 'errors.validator.range';

    /**
     * @param array $values
     * @return bool
     * @throws Exception
     */
    protected function test($values)
    {
        if ($values['min'] === $values['max']) {
            return true;
        } elseif ($values['min'] < $values['max']) {
            return true;
        } elseif ($values['min'] > $values['max'] && ($values['max'] === 0)) {
            return true;
        }

        return false;
    }
}
