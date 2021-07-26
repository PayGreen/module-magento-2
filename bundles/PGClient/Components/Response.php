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

namespace PGI\Module\PGClient\Components;

use PGI\Module\PGClient\Components\Feedback as FeedbackComponent;
use PGI\Module\PGClient\Components\Request as RequestComponent;

/**
 * Class Response
 * @package PGClient\Components
 */
class Response
{
    /** @var RequestComponent The original request. */
    private $request;

    /** @var mixed Data of the response.*/
    public $data;

    /** @var int */
    private $httpCode;

    /**
     * Response constructor.
     * @param FeedbackComponent $feedback
     */
    public function __construct(FeedbackComponent $feedback)
    {
        $this->request = $feedback->getRequest();
        $this->httpCode = (int) $feedback->getCode();
        $this->data = $this->format($feedback->getContent());
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function format($data)
    {
        return $data;
    }

    /**
     * @return RequestComponent
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return int
     */
    public function getHTTPCode()
    {
        return $this->httpCode;
    }

    public function toArray()
    {
        return array(
            'http_code' => $this->getHTTPCode(),
            'data' => $this->data
        );
    }
}
