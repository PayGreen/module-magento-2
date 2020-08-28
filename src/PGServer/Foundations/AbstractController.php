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
 * @version   1.0.1
 */

/**
 * Class PGServerFoundationsAbstractController
 * @package PGServer\Foundations
 */
abstract class PGServerFoundationsAbstractController extends PGFrameworkFoundationsAbstractObject
{
    /** @var PGFrameworkServicesNotifier */
    private $notifier;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    /** @var PGServerServicesLinker */
    private $linker;

    /** @var PGServerFoundationsAbstractRequest */
    private $request;

    public function __construct(
        PGFrameworkServicesNotifier $notifier,
        PGFrameworkServicesLogger $logger,
        PGServerServicesLinker $linker
    ) {
        $this->notifier = $notifier;
        $this->logger = $logger;
        $this->linker = $linker;
    }

    /**
     * @return PGFrameworkServicesLogger
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
     * @return PGServerServicesLinker
     */
    protected function getLinker()
    {
        return $this->linker;
    }

    /**
     * @param PGServerFoundationsAbstractRequest $request
     * @return self
     */
    public function setRequest(PGServerFoundationsAbstractRequest $request)
    {
        $this->request = $request;

        return $this;
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
     * @return PGServerComponentsResponsesArrayResponse
     * @throws Exception
     */
    protected function buildArrayResponse(array $data = array())
    {
        $response = new PGServerComponentsResponsesArrayResponse($this->getRequest());

        $response->tag('API');

        $response->setData($data);

        return $response;
    }

    /**
     * @return PGServerComponentsResponsesEmptyResponse
     * @throws Exception
     */
    protected function buildEmptyResponse()
    {
        $response = new PGServerComponentsResponsesEmptyResponse($this->getRequest());

        $response->tag('API');

        return $response;
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
     * @throws PGServerExceptionsHTTPNotFoundException
     */
    protected function notFound()
    {
        throw new PGServerExceptionsHTTPNotFoundException();
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

    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    protected function buildTemplateResponse($template, array $data = array())
    {
        $response = new PGServerComponentsResponsesTemplateResponse($this->getRequest());

        $response
            ->tag('PGTemplate')
            ->setTemplate($template)
            ->setData($data)
        ;

        return $response;
    }

    protected function delegate($name, array $config = array())
    {
        $serviceName = "action.$name";

        $this->logger->debug("Delegate resolving action to '$serviceName'.");

        /** @var PGServerInterfacesActionInterface $action */
        $action = $this->getService($serviceName);

        if (! $action instanceof PGServerInterfacesActionInterface) {
            throw new Exception("Target service '$serviceName' is not a valid action.");
        }

        $action->addConfig($config)->setRequest($this->getRequest());

        return $action;
    }

    /**
     * @param string $name
     * @param array $data
     * @return PGFormInterfacesFormInterface
     * @throws Exception
     */
    protected function buildForm($name, array $data = array())
    {
        /** @var PGFormServicesFormBuilder $formBuilder */
        $formBuilder = $this->getService('builder.form');

        return $formBuilder->build($name, $data);
    }
}
