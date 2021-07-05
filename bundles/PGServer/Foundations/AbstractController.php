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
 * @version   2.1.0
 *
 */

/**
 * Class PGServerFoundationsAbstractController
 * @package PGServer\Foundations
 */
abstract class PGServerFoundationsAbstractController
{
    /** @var PGFrameworkServicesNotifier */
    private $notifier;

    /** @var PGModuleServicesLogger */
    private $logger;

    /** @var PGServerServicesHandlersLink */
    private $linkHandler;

    /** @var PGServerFoundationsAbstractRequest */
    private $request;

    /** @var PGModuleServicesSettings */
    private $settings;

    /** @var PGSystemComponentsParameters */
    private $parameters;

    /** @var PGFormServicesFormBuilder */
    private $formBuilder;

    /**
     * @param PGFormServicesFormBuilder
     */
    public function setFormBuilder(PGFormServicesFormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }

    /**
     * @return PGModuleServicesLogger
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param PGModuleServicesLogger
     */
    public function setLogger(PGModuleServicesLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return PGFrameworkServicesNotifier
     */
    protected function getNotifier()
    {
        return $this->notifier;
    }

    /**
     * @param PGFrameworkServicesNotifier
     */
    public function setNotifier(PGFrameworkServicesNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * @return PGServerServicesHandlersLink
     */
    protected function getLinkHandler()
    {
        return $this->linkHandler;
    }

    /**
     * @param PGServerServicesHandlersLink
     */
    public function setLinkHandler(PGServerServicesHandlersLink $linkHandler)
    {
        $this->linkHandler = $linkHandler;
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
     * @return PGModuleServicesSettings
     */
    protected function getSettings()
    {
        return $this->settings;
    }

    public function setSettings(PGModuleServicesSettings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return PGSystemComponentsParameters
     */
    protected function getParameters()
    {
        return $this->parameters;
    }

    public function setParameters(PGSystemComponentsParameters $parameters)
    {
        $this->parameters = $parameters;
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

    /**
     * @param string $name
     * @param array $data
     * @return PGFormInterfacesFormInterface
     * @throws Exception
     */
    protected function buildForm($name, array $data = array())
    {
        return $this->formBuilder->build($name, $data);
    }
}
