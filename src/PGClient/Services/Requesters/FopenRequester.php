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
 * @version   0.3.4
 */

/**
 * Class PGClientServicesRequestersFopenRequester
 * @package PGClient\Services\Requesters
 */
class PGClientServicesRequestersFopenRequester extends PGClientFoundationsAbstractRequester
{
    public function isValid(PGClientEntitiesRequest $request)
    {
        return $this->checkCompatibility();
    }

    public function sendRequest(PGClientEntitiesRequest $request)
    {
        $options = array(
            'http' => array(
                'method'  => $request->getMethod(),
                'header'  => join("\r\n", $request->getHeaders()),
                'content' => $request->getContent()
            )
        );

        $context = stream_context_create($options);
        $http_response_header = null;

        $rawResult = file_get_contents($request->getFinalUrl(), false, $context);

        $details = $this->parseHeaders($http_response_header);

        if (empty($rawResult) && ($details['response_code'] === 500)) {
            $this->log('alert', 'Unknown error 500 with empty response.', $options, $details);
        }

        return $this->buildResponse($request, $details['response_code'], $rawResult, $details);
    }

    public function parseHeaders($headers)
    {
        $head = array();
        foreach ($headers as $v) {
            $t = explode(':', $v, 2);
            if (isset($t[1])) {
                $head[trim($t[0])] = trim($t[1]);
            } else {
                $head[] = $v;
                if (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out)) {
                    $head['response_code'] = (int) $out[1];
                }
            }
        }

        return $head;
    }

    public function checkCompatibility()
    {
        return (bool) ini_get('allow_url_fopen');
    }
}
