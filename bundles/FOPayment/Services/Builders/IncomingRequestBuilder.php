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

namespace PGI\Module\FOPayment\Services\Builders;

use PGI\Module\PGServer\Exceptions\HTTPBadRequest as HTTPBadRequestException;
use PGI\Module\PGServer\Services\Builders\RequestBuilder;

/**
 * Class IncomingRequestBuilder
 * @package FOPayment\Services\Builders
 */
class IncomingRequestBuilder extends RequestBuilder
{
    /**
     * @return array
     * @throws HTTPBadRequestException
     */
    protected function getRequestParameters()
    {
        list($action, $data) = parent::getRequestParameters();

        if (isset($this->getAdapter['pid'])) {
            $data['pid'] = $this->getAdapter['pid'];
        } elseif (isset($this->postAdapter['pid'])) {
            $data['pid'] = $this->postAdapter['pid'];
        }

        return array($action, $data);
    }
}
