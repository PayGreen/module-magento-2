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

namespace PGI\Module\PGForm\Services\Validators;

use PGI\Module\PGForm\Foundations\AbstractValidator;
use PGI\Module\PGFramework\Services\Handlers\SelectHandler;
use Exception;

/**
 * Class ArrayInValidator
 * @package PGForm\Services\Validators
 */
class ArrayInValidator extends AbstractValidator
{
    const ERROR_TRANSLATION_KEY = 'errors.validator.array_in';

    /** @var SelectHandler */
    private $selectHandler;

    public function __construct(SelectHandler $selectHandler)
    {
        $this->selectHandler = $selectHandler;
    }

    /**
     * @param string $value
     * @return bool
     * @throws Exception
     */
    protected function test($value)
    {
        return in_array($value, $this->getValues());
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getErrorData()
    {
        $values = array();
        $length = 0;

        foreach ($this->getValues() as $value) {
            if (($length === 0) || ($length < 100)) {
                $length += strlen($value);
                $values[] = $value;
            } elseif ($length > 0) {
                $values[] = '...';
            }
        }

        return array(
            'values' => implode(', ', $values)
        );
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getValues()
    {
        $values = $this->getDefaultConfig('values');

        if (!is_array($values)) {
            $values = $this->selectHandler->getKeys($values);
        }

        return $values;
    }
}
