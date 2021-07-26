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

namespace PGI\Module\PGForm\Foundations;

use PGI\Module\PGForm\Interfaces\FormatterInterface;
use Exception;

/**
 * Class AbstractFormatter
 * @package PGForm\Foundations
 */
abstract class AbstractFormatter implements FormatterInterface
{
    const TEXT = null;

    private $error = false;

    /** @inheritDoc */
    public function format($value)
    {
        $result = null;

        try {
            $result = $this->process($value);
        } catch (Exception $exception) {
            $this->error = static::TEXT;
        }

        return $result;
    }

    abstract protected function process($value);

    /** @inheritDoc */
    public function isValid()
    {
        return ($this->error === false);
    }

    /** @inheritDoc */
    public function getError()
    {
        return $this->error;
    }
}
