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

namespace PGI\Module\PGFramework\Services\Superglobals;

use PGI\Module\PGFramework\Foundations\AbstractSuperglobal;
use Exception;

/**
 * Class PostSuperglobal
 * @package PGFramework\Services\Superglobals
 */
class PostSuperglobal extends AbstractSuperglobal
{
    public function __construct()
    {
        parent::__construct($_POST);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws Exception
     */
    public function offsetSet($name, $value)
    {
        throw new Exception('Read-only: you cannot set value to $_POST.');
    }

    public function offsetUnset($name)
    {
        throw new Exception('Read-only: you cannot unset value from $_POST.');
    }
}
