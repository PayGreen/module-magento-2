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
 * @version   2.0.1
 *
 */

/**
 * Class APIPaymentComponentsResponse
 * @package APIPayment\Components
 */
class APIPaymentComponentsResponse extends PGClientComponentsResponse
{
    /** @var int */
    private $code;

    /** @var bool */
    private $success;

    /** @var string */
    private $message = null;


    /**
     * APIPaymentComponentsResponse constructor.
     * @param stdClass $data
     * @param int $httpCode
     * @throws PGClientExceptionsResponseMalformed
     */
    public function __construct(stdClass $data, $httpCode)
    {
        if (empty($data)
            || !property_exists($data, 'success')
            || !property_exists($data, 'message')
            || !property_exists($data, 'code')
            || !property_exists($data, 'data')
        ) {
            throw new PGClientExceptionsResponseMalformed("Malformed response.");
        }

        parent::__construct($data->data, $httpCode);

        $this->code = (int) $data->code;
        $this->success = (bool) $data->success;
        $this->message = (string) $data->message;
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
}
