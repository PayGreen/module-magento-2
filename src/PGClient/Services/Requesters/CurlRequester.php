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
 * @version   1.2.5
 *
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
     * @throws Exception
     */
    public function sendRequest(PGClientEntitiesRequest $request)
    {
        $ch = curl_init();

        $postFields = $request->getContent();

        if ($this->getSetting('ssl_security_skip')) {
            $verifyPeer = false;
            $verifyHost = 0;
        } else {
            $verifyPeer = (bool) $this->getConfig('verify_peer');
            $verifyHost = (int) $this->getConfig('verify_host');
        }

        $options = array(
            CURLOPT_SSL_VERIFYPEER => $verifyPeer,
            CURLOPT_SSL_VERIFYHOST => $verifyHost,
            CURLOPT_URL => $request->getFinalUrl(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_MAXREDIRS => 0,
            CURLOPT_ENCODING => '',
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

        switch ($http_version) {
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
