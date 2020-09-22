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

/**
 * Class PGClientServicesRequestFactory
 * @package PGClient\Services
 */
class PGClientServicesRequestFactory
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
     * @return PGClientEntitiesRequest
     */
    public function buildRequest($name, array $parameters = array())
    {
        if (!isset($this->requestDefinitions[$name])) {
            throw new \LogicException("Unknown request type : '$name'.");
        }

        $definition = $this->requestDefinitions[$name];

        $method = isset($definition['method']) ? $definition['method'] : 'GET';
        $requestHeaders = isset($definition['headers']) ? $definition['headers'] : array();

        $request = new PGClientEntitiesRequest($name, $definition['url']);

        $request
            ->setMethod($method)
            ->addHeaders($this->sharedHeaders)
            ->addHeaders($requestHeaders)
            ->setParameters(array_merge($this->sharedParameters, $parameters))
        ;

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
}
