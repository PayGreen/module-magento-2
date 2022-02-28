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

namespace PGI\Module\APIPayment\Components;

use PGI\Module\PGClient\Components\ResponseJSON as ResponseJSONComponent;
use PGI\Module\PGClient\Exceptions\ResponseMalformed as ResponseMalformedException;

/**
 * Class Response
 * @package APIPayment\Components
 */
class Response extends ResponseJSONComponent
{
    /** @var int */
    private $code;

    /** @var bool */
    private $success;

    /** @var string */
    private $message = null;

    /**
     * @inheritDoc
     * @throws ResponseMalformedException
     */
    public function format($data)
    {
        $data = parent::format($data);

        if (empty($data)
            || !property_exists($data, 'success')
            || !property_exists($data, 'message')
            || !property_exists($data, 'code')
            || !property_exists($data, 'data')
        ) {
            throw new ResponseMalformedException("Malformed response.");
        }

        $this->code = (int) $data->code;
        $this->success = (bool) $data->success;
        $this->message = (string) $data->message;

        return $data->data;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function toArray()
    {
        return array_merge(
            array(
                'message' => $this->getMessage(),
                'success' => $this->isSuccess(),
                'code' => $this->getCode()
            ),
            parent::toArray()
        );
    }
}
