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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGServer\Foundations;

use PGI\Module\PGFramework\Services\Notifier;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGServer\Components\Requests\Forward as ForwardRequestComponent;
use PGI\Module\PGServer\Components\Responses\Forward as ForwardResponseComponent;
use PGI\Module\PGServer\Components\Responses\HTTP as HTTPResponseComponent;
use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use PGI\Module\PGServer\Exceptions\HTTPUnauthorized as HTTPUnauthorizedException;
use PGI\Module\PGServer\Foundations\AbstractRequest;
use PGI\Module\PGServer\Interfaces\DeflectorInterface;
use PGI\Module\PGServer\Services\Handlers\LinkHandler;
use Exception;

/**
 * Class AbstractDeflector
 * @package PGServer\Foundations
 */
abstract class AbstractDeflector implements DeflectorInterface
{
    /** @var Notifier */
    private $notifier;

    /** @var Logger */
    private $logger;

    /** @var LinkHandler */
    private $linkHandler;

    /** @var AbstractRequest */
    private $request;

    /**
     * @param LinkHandler $linkHandler
     */
    public function setLinkHandler(LinkHandler $linkHandler)
    {
        $this->linkHandler = $linkHandler;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Notifier $notifier
     */
    public function setNotifier(Notifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function process(AbstractRequest $request)
    {
        $this->request = $request;

        return $this->buildResponse();
    }

    /**
     * @return HTTPResponseComponent
     */
    abstract protected function buildResponse();

    /**
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return Notifier
     */
    protected function getNotifier()
    {
        return $this->notifier;
    }

    /**
     * @return LinkHandler
     */
    protected function getLinkHandler()
    {
        return $this->linkHandler;
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
     * @param string|null $text
     * @throws HTTPUnauthorizedException
     */
    protected function unauthorized($text = null)
    {
        throw new HTTPUnauthorizedException($text);
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
}
