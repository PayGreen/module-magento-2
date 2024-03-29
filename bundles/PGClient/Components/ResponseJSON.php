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

namespace PGI\Module\PGClient\Components;

use PGI\Module\PGClient\Components\Response as ResponseComponent;
use PGI\Module\PGClient\Exceptions\ResponseMalformed as ResponseMalformedException;
use PGI\Module\PGSystem\Services\Container;
use stdClass;

/**
 * Class ResponseJSON
 * @package PGClient\Components
 */
class ResponseJSON extends ResponseComponent
{
    /**
     * @param string $data
     * @return stdClass
     * @throws ResponseMalformedException
     */
    protected function format($data)
    {
        $data = parent::format($data);

        $decodedata = @json_decode($data);

        if (!$decodedata instanceof stdClass) {
            $logger = Container::getInstance()->get('logger');
            $logger->critical($data);
            $logger->critical($decodedata);

            throw new ResponseMalformedException("Invalid JSON result.");
        }

        return $decodedata;
    }
}
