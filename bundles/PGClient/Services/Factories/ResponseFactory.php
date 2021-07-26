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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGClient\Services\Factories;

use PGI\Module\PGClient\Components\Feedback as FeedbackComponent;
use PGI\Module\PGClient\Components\Request as RequestComponent;
use PGI\Module\PGClient\Components\Response as ResponseComponent;
use PGI\Module\PGClient\Components\ValidityRangeList as ValidityRangeListComponent;
use PGI\Module\PGClient\Exceptions\ResponseFailed as ResponseFailedException;
use PGI\Module\PGClient\Exceptions\ResponseHTTPError as ResponseHTTPErrorException;
use PGI\Module\PGClient\Exceptions\ResponseMalformed as ResponseMalformedException;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGSystem\Components\Bag as BagComponent;
use Exception;

/**
 * Class ResponseFactory
 * @package PGClient\Services\Factories
 */
class ResponseFactory
{
    private static $DEFAULT_CONFIG = array(
        'class' => 'PGI\Module\PGClient\Components\Response',
        'validity' => '200',
        'strict' => false
    );

    /** @var Logger Service to log requests, responses and errors. */
    private $logger;

    /** @var BagComponent List of request definitions. */
    private $requestDefinitions = array();

    /** @var BagComponent */
    private $config;

    /**
     * RequestSender constructor.
     * @param Logger $logger
     */
    public function __construct(Logger $logger, array $requestDefinitions, array $config)
    {
        $this->logger = $logger;
        $this->requestDefinitions = new BagComponent($requestDefinitions);

        $this->config = new BagComponent(self::$DEFAULT_CONFIG);
        $this->config->merge($config);
    }

    /**
     * @param FeedbackComponent $feedback
     * @return ResponseComponent
     * @throws ResponseMalformedException
     * @throws ResponseHTTPErrorException
     * @throws ResponseFailedException
     * @throws Exception
     */
    public function build(FeedbackComponent $feedback)
    {
        $responseClass = $this->getResponseClass($feedback->getRequest());

        try {
            /** @var ResponseComponent $response */
            $response = new $responseClass($feedback);

            if (!$response instanceof ResponseComponent) {
                $class = get_class($response);
                throw new Exception(
                    "Response component must inherit from ResponseComponent. '$class' class defined."
                );
            }

            $this->log('info', 'Building api response.', $response->toArray());
        } catch (ResponseMalformedException $exception) {
            $text = "Malformed response. (HTTP : {$feedback->getCode()})";
            $this->log('critical', $text, $feedback->getContent(), $feedback->getDetails());

            throw $exception;
        }

        if (!$this->isValidResponse($response)) {
            $text = "Invalid response. (HTTP : {$feedback->getCode()})";
            $this->log('error', $text, $feedback->getContent(), $feedback->getDetails());

            throw new ResponseHTTPErrorException($text, $feedback->getCode());
        }

        if (!$this->isSuccessResponse($response)) {
            $text = "Unsuccessfull response. (HTTP : {$feedback->getCode()})";
            $this->log('error', $text, $feedback->getContent(), $feedback->getDetails());

            throw new ResponseFailedException($text);
        }

        return $response;
    }

    protected function isValidResponse(ResponseComponent $response)
    {
        $validityRanges = (string) $this->getConfig('validity', $response->getRequest());

        $validityRanges = new ValidityRangeListComponent($validityRanges);

        return $validityRanges->isValid($response->getHTTPCode());
    }

    /**
     * @param ResponseComponent $response
     * @return bool
     * @throws Exception
     */
    protected function isSuccessResponse(ResponseComponent $response)
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
     * @param RequestComponent $request
     * @return string
     * @throws Exception
     */
    protected function getResponseClass(RequestComponent $request)
    {
        return (string) $this->getConfig('class', $request);
    }

    protected function getConfig($key, RequestComponent $request)
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
