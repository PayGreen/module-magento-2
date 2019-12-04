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
 * Class PGClientServicesRequestersCurlRequester
 * @package PGClient\Services\Requesters
 */
class PGClientServicesRequestersCurlRequester extends PGClientFoundationsAbstractRequester
{
    public function isValid(PGClientEntitiesRequest $request)
    {
        return $this->checkCompatibility();
    }

    /**
     * @param PGClientEntitiesRequest $request
     * @return mixed|PGClientEntitiesResponse
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function sendRequest(PGClientEntitiesRequest $request)
    {
        $ch = curl_init();

        $postFields = $request->getContent();

        $options = array(
            CURLOPT_SSL_VERIFYPEER => (bool) $this->getConfig('verify_peer'),
            CURLOPT_SSL_VERIFYHOST => (int) $this->getConfig('verify_host'),
            CURLOPT_URL => $request->getFinalUrl(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => (bool) $this->getConfig('allow_redirection'),
            CURLOPT_ENCODING => (string) $this->getConfig('encoding'),
            CURLOPT_MAXREDIRS => (bool) $this->getConfig('allow_redirection') ? 10 : 0,
            CURLOPT_CONNECTTIMEOUT => (int) $this->getConfig('timeout'),
            CURLOPT_TIMEOUT => (int) $this->getConfig('timeout'),
            CURLOPT_HTTP_VERSION => $this->getHttpVersionOption(),
            CURLOPT_CUSTOMREQUEST => $request->getMethod(),
            CURLOPT_POSTFIELDS => empty($postFields) ? '' : json_encode($postFields),
            CURLOPT_HTTPHEADER => $request->getHeaders()
        );

        curl_setopt_array($ch, $options);

        $rawResult = curl_exec($ch);

        $details = curl_getinfo($ch);
        $code = $details['http_code'];
        $errno = curl_errno($ch);
        $error = curl_error($ch);

        curl_close($ch);

        if (empty($rawResult) && ($code === 500)) {
            $this->log('alert', 'Unknown error 500 with empty response.', $options, $details);
        }

        if ($errno > 0) {
            throw new PGClientExceptionsPaymentRequestException("[CURL error #$errno] $error");
        }

        return $this->buildResponse($request, $code, $rawResult, $details);
    }

    /**
     * @return int
     * @throws PGClientExceptionsPaymentRequestException
     */
    protected function getHttpVersionOption()
    {
        $http_version = (string) $this->getConfig('http_version');

        switch($http_version) {
            case '':
                $option = CURL_HTTP_VERSION_NONE;
                break;
            case '1':
            case '1.0':
                $option = CURL_HTTP_VERSION_1_0;
                break;
            case '1.1':
                $option = CURL_HTTP_VERSION_1_1;
                break;
            case '2':
            case '2.0':
                $option = CURL_HTTP_VERSION_2;
                break;
            case '2-TLS':
                $option = CURL_HTTP_VERSION_2TLS;
                break;
            case '!2':
                $option = CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE;
                break;
            case '3':
            case '3.0':
                $option = CURL_HTTP_VERSION_3;
                break;
            default:
                throw new PGClientExceptionsPaymentRequestException("Unknown CURLOPT_HTTP_VERSION option : '$http_version'.");
        }

        return $option;
    }

    public function checkCompatibility()
    {
        return extension_loaded('curl');
    }
}
