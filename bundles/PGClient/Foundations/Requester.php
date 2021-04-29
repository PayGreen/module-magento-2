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
 * Class PGClientFoundationsRequester
 * @package PGClient\Foundations
 */
abstract class PGClientFoundationsRequester implements PGClientInterfacesRequester
{
    const DEFAULT_RESPONSE_COMPONENT = 'PGClientComponentsResponse';

    /** @var callable|null Service to log requests, responses and errors. */
    private $logger = null;

    /** @var PGSystemComponentsBag */
    private $config;

    /** @var PGModuleServicesSettings */
    private $settings;

    /**
     * PGClientFoundationsRequester constructor.
     * @param PGModuleServicesSettings $settings
     * @param null $logger
     * @param array $config
     */
    public function __construct(PGModuleServicesSettings $settings, $logger = null, $config = array())
    {
        $this->settings = $settings;
        $this->logger = $logger;
        $this->config = new PGSystemComponentsBag($config ? $config : array());
    }

    protected function getConfig($key)
    {
        return $this->config[$key];
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function getSetting($name)
    {
        return $this->settings->get($name);
    }

    /**
     * @inheritdoc
     */
    public function send(PGClientComponentsRequest $request)
    {
        $request->markAsSent();

        return $this->sendRequest($request);
    }

    /**
     * @param PGClientComponentsRequest $request
     * @return mixed
     * @throw Exception
     */
    abstract public function sendRequest(PGClientComponentsRequest $request);

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

    /**
     * @param PGClientComponentsRequest $request
     * @param int $code
     * @param string $rawResult
     * @param array $details
     * @return PGClientFoundationsResponse
     * @throws PGClientExceptionsResponse
     * @throws Exception
     */
    protected function buildResponse(PGClientComponentsRequest $request, $code, $rawResult, $details)
    {
        if ($code >= 200 && ($code < 300)) {
            $result = $this->decodeResponseRawResult($rawResult, $details);

            $responseClass = (string) $this->getConfig('response_class');
            if (empty($responseClass)) {
                $responseClass = self::DEFAULT_RESPONSE_COMPONENT;
            }

            if (!is_subclass_of($responseClass, 'PGClientFoundationsResponse')) {
                throw new Exception(
                    "Response component must inherit from PGClientFoundationsResponse. '$responseClass' class defined."
                );
            }

            /** @var PGClientFoundationsResponse $response */
            $response = new $responseClass($result, $code);

            $response->setRequest($request);

            return $response;
        } else {
            $text = "Request not successed. (HTTP : $code)";
            $this->log('critical', $text, $rawResult, $details);

            try {
                $result = $this->decodeResponseRawResult($rawResult, $details);
                throw new PGClientExceptionsResponseFailed($result->message, $result->code);
            } catch (PGClientExceptionsResponseMalformed $exception) {
                throw new PGClientExceptionsResponseHTTPError($text, $code);
            }
        }
    }

    /**
     * @param string $rawResult
     * @param array $details
     * @return stdClass
     * @throws PGClientExceptionsResponseMalformed
     */
    protected function decodeResponseRawResult($rawResult, $details)
    {
        $result = @json_decode($rawResult);

        if (!$result instanceof stdClass) {
            $text = "Invalid JSON result.";

            $this->log('critical', $text, $rawResult, $details);

            throw new PGClientExceptionsResponseMalformed($text);
        } else {
            return $result;
        }
    }
}