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

namespace PGI\Module\PGServer\Components\Responses;

use PGI\Module\PGServer\Components\Requests\Forward as ForwardRequestComponent;
use PGI\Module\PGServer\Foundations\AbstractResponse;
use Exception;

/**
 * Class Forward
 * @package PGServer\Components\Responses
 */
class Forward extends AbstractResponse
{
    /**
     * Forward constructor.
     * @inheritDoc
     * @throws Exception
     */
    public function __construct($previous)
    {
        if (! $previous instanceof ForwardRequestComponent) {
            throw new Exception("ForwardResponse only accept RequestForward.");
        }

        parent::__construct($previous);
    }
}
