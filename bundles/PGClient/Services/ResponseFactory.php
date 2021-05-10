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
 * @version   2.0.2
 *
 */

/**
 * Class PGClientServicesResponseFactory
 * @package PGClient\Services
 */
class PGClientServicesResponseFactory
{
    private static $DEFAULT_CONFIG = array(
        'class' => 'PGClientComponentsResponse',
        'validity' => '200',
        'strict' => false
    );

    /** @var PGModuleServicesLogger Service to log requests, responses and errors. */
    private $logger;

    /** @var PGSystemComponentsBag List of request definitions. */
    private $requestDefinitions = array();

    /** @var PGSystemComponentsBag */
    private $config;

    /**
     * RequestSender constructor.
     * @param PGModuleServicesLogger $logger
     */
    public function __construct(PGModuleServicesLogger $logger, array $requestDefinitions, array $config)
    {
        $this->logger = $logger;
        $this->requestDefinitions = new PGSystemComponentsBag($requestDefinitions);

        $this->config = new PGSystemComponentsBag(self::$DEFAULT_CONFIG);
        $this->config->merge($config);
    }

    /**
     * @param PGClientComponentsFeedback $feedback
     * @return PGClientComponentsResponse
     * @throws PGClientExceptionsResponseMalformed
     * @throws PGClientExceptionsResponseHTTPError
     * @throws PGClientExceptionsResponseFailed
     * @throws Exception
     */
    public function build(PGClientComponentsFeedback $feedback)
    {
        $responseClass = $this->getResponseClass($feedback->getRequest());

        try {
            /** @var PGClientComponentsResponse $response */
            $response = new $responseClass($feedback);

            if (!$response instanceof PGClientComponentsResponse) {
                $class = get_class($response);
                throw new Exception(
                    "Response component must inherit from PGClientComponentsResponse. '$class' class defined."
                );
            }

            $this->log('info', 'Building api response.', $response->toArray());
        } catch (PGClientExceptionsResponseMalformed $exception) {
            $text = "Malformed response. (HTTP : {$feedback->getCode()})";
            $this->log('critical', $text, $feedback->getContent(), $feedback->getDetails());

            throw $exception;
        }

        if (!$this->isValidResponse($response)) {
            $text = "Invalid response. (HTTP : {$feedback->getCode()})";
            $this->log('error', $text, $feedback->getContent(), $feedback->getDetails());

            throw new PGClientExceptionsResponseHTTPError($text, $feedback->getCode());
        }

        if (!$this->isSuccessResponse($response)) {
            $text = "Unsuccessfull response. (HTTP : {$feedback->getCode()})";
            $this->log('error', $text, $feedback->getContent(), $feedback->getDetails());

            throw new PGClientExceptionsResponseFailed($text);
        }

        return $response;
    }

    protected function isValidResponse(PGClientComponentsResponse $response)
    {
        $validityRanges = (string) $this->getConfig('validity', $response->getRequest());

        $validityRanges = new PGClientComponentsValidityRangeList($validityRanges);

        return $validityRanges->isValid($response->getHTTPCode());
    }

    /**
     * @param PGClientComponentsResponse $response
     * @return bool
     * @throws Exception
     */
    protected function isSuccessResponse(PGClientComponentsResponse $response)
    {
        $strict = (bool) $this->getConfig('strict', $response->getRequest());

        if (!$strict) {
            return true;
        } elseif (method_exists($response, 'isSuccess')) {
            return $response->isSuccess();
        }

        throw new Exception("Response does not have 'isSuccess' method.");
    }

    /**
     * @param PGClientComponentsRequest $request
     * @return string
     * @throws Exception
     */
    protected function getResponseClass(PGClientComponentsRequest $request)
    {
        return (string) $this->getConfig('class', $request);
    }

    protected function getConfig($key, PGClientComponentsRequest $request)
    {
        $name = $request->getName();
        $value = $this->requestDefinitions["$name.$key"];

        if ($value === null) {
            $value = $this->config[$key];
        }

        return $value;
    }

    /**
     * @param string $level
     * @param string $message
     * @param mixed $result
     * @param array $details
     */
    protected function log($level, $message, $result, array $details = array())
    {
        if ($this->logger !== null) {
            if (empty($details)) {
                $data = $result;
            } else {
                $data = array(
                    'result' => $result,
                    'details' => $details
                );
            }

            call_user_func(array($this->logger, $level), $message, $data);
        }
    }
}
