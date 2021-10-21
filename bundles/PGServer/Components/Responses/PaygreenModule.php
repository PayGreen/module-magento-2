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
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGServer\Components\Responses;

use PGI\Module\PGServer\Components\Responses\Collection as CollectionResponseComponent;

/**
 * Class PaygreenModule
 * @package PGServer\Components\Responses
 */
class PaygreenModule extends CollectionResponseComponent
{
    private $success = false;

    /**
     * @return self
     */
    public function validate()
    {
        $this->success = true;

        return $this;
    }

    /**
     * @return self
     */
    public function invalidate()
    {
        $this->success = false;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }
}
