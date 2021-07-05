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
 * Class PGClientServicesRequestSender
 * @package PGClient\Services
 */
class PGClientServicesRequestSender
{
    /** @var callable|null Service to log requests, responses and errors. */
    private $logger = null;

    /** @var array List of available requesters. */
    private $requesters = array();

    /** @var PGClientServicesResponseFactory */
    private $responseFactory;

    /**
     * RequestSender constructor.
     * @param callable|null $logger
     */
    public function __construct($logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param PGClientInterfacesRequester $requester
     * @return self
     */
    public function addRequesters(PGClientInterfacesRequester $requester)
    {
        $this->requesters[] = $requester;

        return $this;
    }

    /**
     * @param PGClientServicesResponseFactory $responseFactory
     * @return self
     */
    public function setResponseFactory($responseFactory)
    {
        $this->responseFactory = $responseFactory;

        return $this;
    }

    /**
     * @param PGClientComponentsRequest $request
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    public function sendRequest(PGClientComponentsRequest $request)
    {
        $this->log('info', 'Sending an HTTP request.', $request);

        $feedback = null;

        $microtime = $this->getMicroTime();

        try {
            /** @var PGClientInterfacesRequester $requester */
            foreach ($this->requesters as $requester) {
                if (!$request->isSent() && $requester->isValid($request)) {
                    $requesterName = get_class($requester);
                    $this->logger->debug("Send request with requester : '$requesterName'.");

                    /** @var PGClientComponentsFeedback $feedback */
                    $feedback = $requester->send($request);
                }
            }
        } catch (Exception $exception) {
            $this->log('critical', 'Request error : ' . $exception->getMessage(), $request);
            throw $exception;
        }

        $duration = $this->getMicroTime() - $microtime;

        if (!$request->isSent()) {
            $message = "Can not find adapted requester to this request.";

            $this->log('critical', $message, $request);

            throw new Exception($message);
        }

        $this->log('debug', 'Receive an HTTP response.', $request, $feedback, $duration);

        return $this->responseFactory->build($feedback);
    }

    private function getMicroTime()
    {
        $mt = explode(' ', microtime());

        return ((int) $mt[1]) * 1000 + ((int) round($mt[0] * 1000));
    }

    /**
     * @param string $level
     * @param string $message
     * @param PGClientComponentsRequest $request
     * @param PGClientComponentsFeedback|null $feedback
     * @param int $duration
     */
    private function log(
        $level,
        $message,
        PGClientComponentsRequest $request,
        PGClientComponentsFeedback $feedback = null,
        $duration = 0
    ) {
        if ($this->logger !== null) {
            $data = array(
                'type' => $request->getName(),
                'method' => $request->getMethod(),
                'headers' => $request->getHeaders(),
                'parameters' => $request->getParameters(),
                'content' => $request->getContent(),
                'raw_url' => $request->getRawUrl(),
                'final_url' => $request->getFinalUrl()
            );

            if ($feedback !== null) {
                $data['response'] = array(
                    'duration' => $duration,
                    'code' => $feedback->getCode(),
                    'content' => $feedback->getContent()
                );
            }

            call_user_func(array($this->logger, $level), $message, $data);
        }
    }

    /**
     * @return bool
     */
    public function checkCompatibility()
    {
        /** @var PGClientInterfacesRequester $requester */
        foreach ($this->requesters as $requester) {
            if ($requester->checkCompatibility()) {
                return true;
            }
        }

        return false;
    }
}
