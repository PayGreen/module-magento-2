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
 * @version   2.1.1
 *
 */

/**
 * Class PGServerFoundationsAbstractDeflector
 * @package PGServer\Foundations
 */
abstract class PGServerFoundationsAbstractDeflector implements PGServerInterfacesDeflectorInterface
{
    /** @var PGFrameworkServicesNotifier */
    private $notifier;

    /** @var PGModuleServicesLogger */
    private $logger;

    /** @var PGServerServicesHandlersLink */
    private $linkHandler;

    /** @var PGServerFoundationsAbstractRequest */
    private $request;

    /**
     * @param PGServerServicesHandlersLink $linkHandler
     */
    public function setLinkHandler(PGServerServicesHandlersLink $linkHandler)
    {
        $this->linkHandler = $linkHandler;
    }

    /**
     * @param PGModuleServicesLogger $logger
     */
    public function setLogger(PGModuleServicesLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param PGFrameworkServicesNotifier $notifier
     */
    public function setNotifier(PGFrameworkServicesNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function process(PGServerFoundationsAbstractRequest $request)
    {
        $this->request = $request;

        return $this->buildResponse();
    }

    /**
     * @return PGServerComponentsResponsesHTTPResponse
     */
    abstract protected function buildResponse();

    /**
     * @return PGModuleServicesLogger
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return PGFrameworkServicesNotifier
     */
    protected function getNotifier()
    {
        return $this->notifier;
    }

    /**
     * @return PGServerServicesHandlersLink
     */
    protected function getLinkHandler()
    {
        return $this->linkHandler;
    }

    /**
     * @return PGServerFoundationsAbstractRequest
     */
    protected function getRequest()
    {
        return $this->request;
    }

    protected function success($text)
    {
        $this->notifier->add(PGFrameworkServicesNotifier::STATE_SUCCESS, $text);

        $this->logger->notice("--SUCCESS--> $text");
    }

    protected function notice($text)
    {
        $this->notifier->add(PGFrameworkServicesNotifier::STATE_NOTICE, $text);

        $this->logger->notice("--NOTICE--> $text");
    }

    protected function failure($text)
    {
        $this->notifier->add(PGFrameworkServicesNotifier::STATE_FAILURE, $text);

        $this->logger->notice("--FAILURE--> $text");
    }

    /**
     * @param string|null $text
     * @throws PGServerExceptionsHTTPUnauthorizedException
     */
    protected function unauthorized($text = null)
    {
        throw new PGServerExceptionsHTTPUnauthorizedException($text);
    }

    /**
     * @param string $url
     * @param int|null $code
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws Exception
     */
    protected function redirect($url, $code = null)
    {
        $response = new PGServerComponentsResponsesRedirectionResponse($this->getRequest());

        $response->setUrl($url);

        if ($code !== null) {
            $response->setRedirectionCode($code);
        }

        return $response;
    }

    /**
     * @param string $target
     * @param array $data
     * @param bool $transmitHeaders
     * @return PGServerComponentsResponsesForwardResponse
     * @throws Exception
     */
    protected function forward($target, array $data = array(), $transmitHeaders = true)
    {
        $headers = $transmitHeaders ? $this->getRequest()->getAllHeaders() :  array();
        $request = new PGServerComponentsRequestsForwardRequest($target, $data, $headers);

        return new PGServerComponentsResponsesForwardResponse($request);
    }
}
