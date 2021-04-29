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
 * @version   2.0.1
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
     */
    public function addRequesters(PGClientInterfacesRequester $requester)
    {
        $this->requesters[] = $requester;

        return $this;
    }

    /**
     * @param PGClientComponentsRequest $request
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponse
     * @throws PGClientExceptionsResponseFailed
     */
    public function sendRequest(PGClientComponentsRequest $request)
    {
        $this->log('debug', 'Sending an api request.', $request);

        $microtime = $this->getMicroTime();

        try {
            /** @var PGClientInterfacesRequester $requester */
            foreach ($this->requesters as $requester) {
                if (!$request->isSent() && $requester->isValid($request)) {
                    $requesterName = get_class($requester);
                    $this->logger->debug("Send request with requester : '$requesterName'.");

                    /** @var PGClientComponentsResponse $data */
                    $response = $requester->send($request);
                }
            }
        } catch (Exception $exception) {
            $this->log('critical', 'Request error : ' . $exception->getMessage(), $request);

            throw new PGClientExceptionsResponse(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        $duration = $this->getMicroTime() - $microtime;

        if (!$request->isSent()) {
            $message = "Can not find adapted requester to this request.";

            $this->log('critical', $message, $request);

            throw new PGClientExceptionsResponse($message);
        }

        if (method_exists($response, 'isSuccess')) {
            if (!$response->isSuccess()) {
                throw new PGClientExceptionsResponseFailed($response->getMessage(), $response->getCode());
            }
        }

        $this->log('info', 'Receive an api response.', $request, $response, $duration);

        return $response;
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
     * @param PGClientComponentsResponse|null $response
     * @param int $duration
     */
    private function log(
        $level,
        $message,
        PGClientComponentsRequest $request,
        PGClientComponentsResponse $response = null,
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

            if ($response !== null) {
                $data = array_merge($data, array(
                    'duration' => $duration,
                    'http' => $response->getHTTPCode(),
                    'response' => $response->data
                ));

                if (method_exists($response, 'getCode')) {
                    $data['code'] = $response->getCode();
                }

                if (method_exists($response, 'isSuccess')) {
                    $data['success'] = $response->isSuccess();
                }

                if (method_exists($response, 'getMessage')) {
                    $data['message'] = $response->getMessage();
                }
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
