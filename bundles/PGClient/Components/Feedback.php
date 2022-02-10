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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGClient\Components;

use PGI\Module\PGClient\Components\Request as RequestComponent;

/**
 * Class Feedback
 * @package PGClient\Components
 */
class Feedback
{
    /** @var RequestComponent The original request. */
    private $request;

    /** @var int */
    private $code;

    /** @var string */
    private $content;

    /** @var array */
    private $details;

    /**
     * Feedback constructor.
     * @param RequestComponent $request
     * @param $code
     * @param $content
     * @param array $details
     */
    public function __construct(RequestComponent $request, $code, $content, array $details = array())
    {
        $this->request = $request;
        $this->code = (int) $code;
        $this->content = $content;
        $this->details = $details;
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }
}
