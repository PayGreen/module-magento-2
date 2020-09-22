<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.1.1
 */

class PGServerServicesRequestBuilder extends PGFrameworkFoundationsAbstractObject
{
    const DEFAULT_DATA_KEY = 'pgdata';
    const DEFAULT_ACTION_KEY = 'pgaction';

    /** @var array */
    private $config = array(
        'data_key' => self::DEFAULT_DATA_KEY,
        'action_key' => self::DEFAULT_ACTION_KEY,
        'strict' => true,
        'catch_errors' => false,
        'add_headers' => true,
        'default_action' => null
    );

    public function __construct(array $config = array())
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getConfig($key)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * @param $context
     * @return PGServerFoundationsAbstractRequest
     * @throws PGServerExceptionsHTTPBadRequestException
     */
    public function buildRequest($context)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        try {
            list($action, $data) = $this->getRequestParameters();

            $logger->debug("Action found : '$action'.");

            $headers = $this->getHeaders();
        } catch (PGServerExceptionsHTTPBadRequestException $exception) {
            if ($this->getConfig('catch_errors') === true) {
                return new PGServerComponentsRequestsEmptyRequest();
            } else {
                throw $exception;
            }
        }

        return new PGServerComponentsRequestsHTTPRequest($action, $data, $headers);
    }

    /**
     * @return array
     * @throws PGServerExceptionsHTTPBadRequestException
     */
    protected function getRequestParameters()
    {
        $isStrict = (bool) $this->getConfig('strict');
        $dataKey = $this->getConfig('data_key');
        $actionKey = $this->getConfig('action_key');

        $action = $this->getConfig('default_action');
        $data = array();

        $get = $this->getQueryData();
        $post = $this->getBodyData();

        if (!$isStrict && array_key_exists($actionKey, $get)) {
            $action = $get[$actionKey];

            if (array_key_exists($dataKey, $get)) {
                $data = array_merge($get[$dataKey], $post);
            } else {
                $data = $post;
            }
        } elseif (array_key_exists($actionKey, $post)) {
            $action = $post[$actionKey];

            if (array_key_exists($dataKey, $post)) {
                $data = $post[$dataKey];
            }
        } elseif ($action === null) {
            throw new PGServerExceptionsHTTPBadRequestException("Incoming request don't have required '$actionKey' key.");
        }

        return array($action, $data);
    }

    /**
     * @return string[]
     */
    protected function getHeaders()
    {
        if ($this->getConfig('add_headers') === false) {
            $headers = array();
        } elseif (function_exists('getallheaders')) {
            $headers = getallheaders();

            if ($headers === false) {
                $headers = array();
            }
        } else {
            $headers = $this->getAllHeaders();
        }

        return $headers;
    }

    /**
     * Get all HTTP header key/values as an associative array for the current request.
     *
     * @see https://github.com/ralouphie/getallheaders
     * @return string[] The HTTP header key/value pairs.
     */
    protected function getAllHeaders()
    {
        $headers = array();

        $copy_server = array(
            'CONTENT_TYPE'   => 'Content-Type',
            'CONTENT_LENGTH' => 'Content-Length',
            'CONTENT_MD5'    => 'Content-Md5',
        );

        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
                if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                    $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                    $headers[$key] = $value;
                }
            } elseif (isset($copy_server[$key])) {
                $headers[$copy_server[$key]] = $value;
            }
        }

        if (!isset($headers['Authorization'])) {
            if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
                $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
                $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
            } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
                $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
            }
        }

        return $headers;
    }

    /**
     * @return array
     * @todo Remove magic quotes management when upgrade to PHP 5.4
     */
    protected function getBodyData()
    {
        $post = $_POST;

        if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
            $post = PGFrameworkToolsArray::stripSlashes($post);
        }

        return $post;
    }

    /**
     * @return array
     * @todo Remove magic quotes management when upgrade to PHP 5.4
     */
    protected function getQueryData()
    {
        $get = $_GET;

        if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
            $get = PGFrameworkToolsArray::stripSlashes($get);
        }

        return $get;
    }
}
