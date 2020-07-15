<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.0
 */

class PGServerServicesRenderersProcessorsWriteHTTPProcessor extends PGFrameworkFoundationsAbstractObject
{
    private $httpVersion;

    private $httpCodes = array();

    public function __construct($httpVersion = "1.0", $httpCodes = array())
    {
        $this->httpVersion = $httpVersion;
        $this->httpCodes = $httpCodes;
    }

    public function process(PGServerComponentsResponsesHTTPResponse $response)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $text = $this->httpCodes[$response->getStatus()];
        header("HTTP/{$this->httpVersion} {$response->getStatus()} $text");

        foreach ($response->getHeaders() as $title => $content) {
            header("$title: $content");
        }

        echo $response->getContent();

        $logger->debug("Output successfully write with HTTP status {$response->getStatus()}.");

        return $response;
    }
}
