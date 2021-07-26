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

namespace PGI\Module\PGClient\Services\Factories;

use PGI\Module\PGClient\Components\Request as RequestComponent;
use LogicException;

/**
 * Class RequestFactory
 * @package PGClient\Services\Factories
 */
class RequestFactory
{
    /** @var array List of request definitions. */
    private $requestDefinitions = array();

    /** @var array List of common headers shared between all requests. */
    private $sharedHeaders = array();

    /** @var array List of common parameters shared between all requests. */
    private $sharedParameters = array();

    /**
     * RequestFactory constructor.
     * @param array $requestDefinitions
     * @param array $sharedHeaders
     * @param array $sharedParameters
     */
    public function __construct(
        array $requestDefinitions,
        array $sharedHeaders = array(),
        array $sharedParameters = array()
    ) {
        $this->requestDefinitions = $requestDefinitions;
        $this->sharedHeaders = $sharedHeaders;
        $this->sharedParameters = $sharedParameters;
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param bool $withBearer
     * @return RequestComponent
     */
    public function buildRequest($name, array $parameters = array(), $withBearer = true)
    {
        if (!isset($this->requestDefinitions[$name])) {
            throw new \LogicException("Unknown request type : '$name'.");
        }

        $definition = $this->requestDefinitions[$name];

        $method = isset($definition['method']) ? $definition['method'] : 'GET';
        $requestHeaders = isset($definition['headers']) ? $definition['headers'] : array();

        $request = new RequestComponent($name, $definition['url']);

        $request
            ->setMethod($method)
            ->addHeaders($this->sharedHeaders)
            ->addHeaders($requestHeaders)
            ->setParameters(array_merge($this->sharedParameters, $parameters))
        ;

        if (!$withBearer) {
            $headers = $this->removeBearer($request->getHeaders());
            $request->setHeaders($headers);
        }

        return $request;
    }

    /**
     * @param array $sharedHeaders
     */
    public function setSharedHeaders(array $sharedHeaders)
    {
        $this->sharedHeaders = $sharedHeaders;
    }

    /**
     * @param array $sharedParameters
     */
    public function setSharedParameters(array $sharedParameters)
    {
        $this->sharedParameters = $sharedParameters;
    }

    /**
     * Return url of preprod or prod
     * @return string url
     */
    public function getAPIHost()
    {
        return $this->sharedParameters['host'];
    }

    /**
     * @param array $headers
     * @return array
     */
    private function removeBearer(array $headers)
    {
        foreach ($headers as $index => $header) {
            if (preg_match('/.*Bearer.*/', $header)) {
                unset($headers[$index]);
                return $headers;
            }
        }

        return $headers;
    }
}
