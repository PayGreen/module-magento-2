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
 * Class PGClientComponentsRequest
 * @package PGClient\Components
 */
class PGClientComponentsRequest
{
    /** @var string Name of the request. */
    private $name;

    /** @var string Method of the request. */
    private $method = 'GET';

    /** @var array List of request headers. */
    private $headers = array();

    /** @var string URL of the request. */
    private $url;

    /** @var array List of request parameters. */
    private $parameters = array();

    /** @var array Content values. */
    private $content = array();

    /** @var bool If request is already sent. */
    private $sent = false;

    public function __construct($name, $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * @param array $parameters
     * @return PGClientComponentsRequest
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param array $headers
     * @return PGClientComponentsRequest
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param array $headers
     * @return PGClientComponentsRequest
     */
    public function addHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * @param string $method
     * @return PGClientComponentsRequest
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param array $content
     */
    public function setContent(array $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getRawUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getFinalUrl()
    {
        $url = $this->url;

        if (preg_match_all('/({(?<keys>[A-Z-_]+)})/i', $url, $results)) {
            foreach ($results['keys'] as $key) {
                if (!array_key_exists($key, $this->parameters)) {
                    throw new \LogicException("Unable to retrieve parameter : '$key'.");
                }

                $url = preg_replace('/{' . $key . '}/i', $this->parameters[$key], $url);
            }
        }

        return $url;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function isSent()
    {
        return $this->sent;
    }

    /**
     * @return PGClientComponentsRequest
     */
    public function markAsSent()
    {
        $this->sent = true;

        return $this;
    }
}
