<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.3
 */

/**
 * Class PGClientFoundationsAbstractRequester
 * @package PGClient\Foundations
 */
abstract class PGClientFoundationsAbstractRequester implements PGClientInterfacesRequesterInterface
{
    /** @var callable|null Service to log requests, responses and errors. */
    private $logger = null;

    /** @var PGFrameworkComponentsBag */
    private $config;

    /**
     * RequestSender constructor.
     * @param callable|null $logger
     */
    public function __construct($logger = null, $config)
    {
        $this->logger = $logger;
        $this->config = new PGFrameworkComponentsBag($config ? $config : array());
    }

    protected function getConfig($key)
    {
        return $this->config[$key];
    }

    /**
     * @inheritdoc
     */
    public function send(PGClientEntitiesRequest $request)
    {
        $request->markAsSent();

        return $this->sendRequest($request);
    }

    /**
     * @param PGClientEntitiesRequest $request
     * @return mixed
     * @throw Exception
     */
    abstract public function sendRequest(PGClientEntitiesRequest $request);

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
     * @param PGClientEntitiesRequest $request
     * @param int $code
     * @param string $rawResult
     * @param array $details
     * @return PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    protected function buildResponse(PGClientEntitiesRequest $request, $code, $rawResult, $details)
    {
        $result = @json_decode($rawResult);

        try {
            if (!$result instanceof stdClass) {
                throw new PGClientExceptionsMalformedResponseException("Invalid JSON result.");
            }

            $response = PGClientEntitiesResponse::buildFromObject($result);

            $response->setRequest($request);
        } catch (PGClientExceptionsMalformedResponseException $exception) {
            $text = $exception->getMessage() . " (HTTP : $code)";

            $this->log('critical', $text, $rawResult, $details);

            throw new PGClientExceptionsPaymentRequestException($text, null);
        }

        if ($code !== 200) {
            $this->log('warning', 'Request not successed.', $result);
        }

        return $response;
    }
}
