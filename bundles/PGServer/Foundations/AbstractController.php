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
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGServer\Foundations;

use PGI\Module\PGForm\Interfaces\FormInterface;
use PGI\Module\PGForm\Services\Builders\FormBuilder;
use PGI\Module\PGFramework\Services\Notifier;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGServer\Components\Requests\Forward as ForwardRequestComponent;
use PGI\Module\PGServer\Components\Responses\Blank as BlankResponseComponent;
use PGI\Module\PGServer\Components\Responses\Collection as CollectionResponseComponent;
use PGI\Module\PGServer\Components\Responses\Forward as ForwardResponseComponent;
use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGServer\Exceptions\HTTPNotFound as HTTPNotFoundException;
use PGI\Module\PGServer\Foundations\AbstractRequest;
use PGI\Module\PGServer\Services\Handlers\LinkHandler;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;
use Exception;

/**
 * Class AbstractController
 * @package PGServer\Foundations
 */
abstract class AbstractController
{
    /** @var Notifier */
    private $notifier;

    /** @var Logger */
    private $logger;

    /** @var LinkHandler */
    private $linkHandler;

    /** @var AbstractRequest */
    private $request;

    /** @var Settings */
    private $settings;

    /** @var ParametersComponent */
    private $parameters;

    /** @var FormBuilder */
    private $formBuilder;

    /**
     * @param FormBuilder
     */
    public function setFormBuilder(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }

    /**
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param Logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return Notifier
     */
    protected function getNotifier()
    {
        return $this->notifier;
    }

    /**
     * @param Notifier
     */
    public function setNotifier(Notifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * @return LinkHandler
     */
    protected function getLinkHandler()
    {
        return $this->linkHandler;
    }

    /**
     * @param LinkHandler
     */
    public function setLinkHandler(LinkHandler $linkHandler)
    {
        $this->linkHandler = $linkHandler;
    }
    

    /**
     * @param AbstractRequest $request
     * @return self
     */
    public function setRequest(AbstractRequest $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return Settings
     */
    protected function getSettings()
    {
        return $this->settings;
    }

    public function setSettings(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return ParametersComponent
     */
    protected function getParameters()
    {
        return $this->parameters;
    }

    public function setParameters(ParametersComponent $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return AbstractRequest
     */
    protected function getRequest()
    {
        return $this->request;
    }

    protected function success($text)
    {
        $this->notifier->add(Notifier::STATE_SUCCESS, $text);

        $this->logger->notice("--SUCCESS--> $text");
    }

    protected function notice($text)
    {
        $this->notifier->add(Notifier::STATE_NOTICE, $text);

        $this->logger->notice("--NOTICE--> $text");
    }

    protected function failure($text)
    {
        $this->notifier->add(Notifier::STATE_FAILURE, $text);

        $this->logger->notice("--FAILURE--> $text");
    }

    /**
     * @return CollectionResponseComponent
     * @throws Exception
     */
    protected function buildArrayResponse(array $data = array())
    {
        $response = new CollectionResponseComponent($this->getRequest());

        $response->tag('API');

        $response->setData($data);

        return $response;
    }

    /**
     * @return BlankResponseComponent
     * @throws Exception
     */
    protected function buildEmptyResponse()
    {
        $response = new BlankResponseComponent($this->getRequest());

        $response->tag('API');

        return $response;
    }

    /**
     * @param string $url
     * @param int|null $code
     * @return RedirectionResponseComponent
     * @throws Exception
     */
    protected function redirect($url, $code = null)
    {
        $response = new RedirectionResponseComponent($this->getRequest());

        $response->setUrl($url);

        if ($code !== null) {
            $response->setRedirectionCode($code);
        }

        return $response;
    }

    /**
     * @throws HTTPNotFoundException
     */
    protected function notFound()
    {
        throw new HTTPNotFoundException();
    }

    /**
     * @param string $target
     * @param array $data
     * @param bool $transmitHeaders
     * @return ForwardResponseComponent
     * @throws Exception
     */
    protected function forward($target, array $data = array(), $transmitHeaders = true)
    {
        $headers = $transmitHeaders ? $this->getRequest()->getAllHeaders() :  array();
        $request = new ForwardRequestComponent($target, $data, $headers);

        return new ForwardResponseComponent($request);
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    protected function buildTemplateResponse($template, array $data = array())
    {
        $response = new TemplateResponseComponent($this->getRequest());

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
     * @return FormInterface
     * @throws Exception
     */
    protected function buildForm($name, array $data = array())
    {
        return $this->formBuilder->build($name, $data);
    }
}
