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

namespace PGI\Module\PGLog\Interfaces;

/**
 * Interface LogInput
 * @package PGLog\Interfaces
 */
interface LoggerInterface
{
    /**
     * @param string $text
     * @param mixed|array|null $data
     * @return void
     */
    public function emergency($text, $data = null);

    /**
     * @param string $text
     * @param mixed|array|null $data
     * @return void
     */
    public function alert($text, $data = null);

    /**
     * @param string $text
     * @param mixed|array|null $data
     * @return void
     */
    public function critical($text, $data = null);

    /**
     * @param string $text
     * @param mixed|array|null $data
     * @return void
     */
    public function error($text, $data = null);

    /**
     * @param string $text
     * @param mixed|array|null $data
     * @return void
     */
    public function warning($text, $data = null);

    /**
     * @param string $text
     * @param mixed|array|null $data
     * @return void
     */
    public function notice($text, $data = null);

    /**
     * @param string $text
     * @param mixed|array|null $data
     * @return void
     */
    public function info($text, $data = null);

    /**
     * @param string $text
     * @param mixed|array|null $data
     * @return void
     */
    public function debug($text, $data = null);
}
