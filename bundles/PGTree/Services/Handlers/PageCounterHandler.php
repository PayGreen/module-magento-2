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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGTree\Services\Handlers;

use PGI\Module\PGFramework\Services\Handlers\CookieHandler;

/**
 * Class PageCounterHandler
 * @package PGTree\Services\Handlers
 */
class PageCounterHandler
{
    const COUNT_PAGE_COOKIE_NAME = 'pgCountPageCookie';

    /** @var CookieHandler */
    private $cookieHandler;

    public function __construct(
        CookieHandler $cookieHandler
    ) {
        $this->cookieHandler = $cookieHandler;
    }

    public function inc()
    {
        $count = $this->get() + 1;

        $this->cookieHandler->set(self::COUNT_PAGE_COOKIE_NAME, $count);
    }

    public function get()
    {
        $count = 0;

        if ($this->cookieHandler->has(self::COUNT_PAGE_COOKIE_NAME)) {
            $count = (int) $this->cookieHandler->get(self::COUNT_PAGE_COOKIE_NAME);
        }

        return $count;
    }

    public function raz()
    {
        $this->cookieHandler->set(self::COUNT_PAGE_COOKIE_NAME, 0);
    }
}