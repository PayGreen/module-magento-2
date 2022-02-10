<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGClient\Services;

use PGI\Module\PGClient\Components\Feedback as FeedbackComponent;
use PGI\Module\PGClient\Components\Request as RequestComponent;
use PGI\Module\PGClient\Components\Response as ResponseComponent;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGClient\Interfaces\RequesterInterface;
use PGI\Module\PGClient\Services\Factories\ResponseFactory;
use Exception;

/**
 * Class Sender
 * @package PGClient\Services
 */
class Sender
{
    /** @var callable|null Service to log requests, responses and errors. */
    private $logger = null;

    /** @var array List of available requesters. */
    private $requesters = array();

    /** @var ResponseFactory */
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
     * @param RequesterInterface $requester
     * @return self
     */
    public function addRequesters(RequesterInterface $requester)
    {
        $this->requesters[] = $requester;

        return $this;
    }

    /**
     * @param ResponseFactory $responseFactory
     * @return self
     */
    public function setResponseFactory($responseFactory)
    {
        $this->responseFactory = $responseFactory;

        return $this;
    }

    /**
     * @param RequestComponent $request
     * @param bool $jsonEncodePostFields
     * @return ResponseComponent
     * @throws \PGI\Module\PGClient\Exceptions\ResponseFailed
     * @throws \PGI\Module\PGClient\Exceptions\ResponseHTTPError
     * @throws \PGI\Module\PGClient\Exceptions\ResponseMalformed
     */
    public function sendRequest(RequestComponent $request, $jsonEncodePostFields = true)
    {
        $this->log('info', 'Sending an HTTP request.', $request);

        $feedback = null;

        $microtime = $this->getMicroTime();

        try {
            /** @var RequesterInterface $requester */
            foreach ($this->requesters as $requester) {
                if (!$request->isSent() && $requester->isValid($request)) {
                    $requesterName = get_class($requester);
                    $this->logger->debug("Send request with requester : '$requesterName'.");

                    /** @var FeedbackComponent $feedback */
                    $feedback = $requester->send($request, $jsonEncodePostFields);
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
     * @param RequestComponent $request
     * @param FeedbackComponent|null $feedback
     * @param int $duration
     */
    private function log(
        $level,
        $message,
        RequestComponent $request,
        FeedbackComponent $feedback = null,
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
        /** @var RequesterInterface $requester */
        foreach ($this->requesters as $requester) {
            if ($requester->checkCompatibility()) {
                return true;
            }
        }

        return false;
    }
}
