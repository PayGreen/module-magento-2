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
 * @version   2.4.0
 *
 */

namespace PGI\Module\APICharity\Services\Factories;

use PGI\Module\APICharity\Services\Facades\ApiFacade;
use PGI\Module\PGClient\Services\Factories\RequestFactory;
use PGI\Module\PGClient\Services\Factories\ResponseFactory;
use PGI\Module\PGClient\Services\Requesters\CurlRequester;
use PGI\Module\PGClient\Services\Requesters\FopenRequester;
use PGI\Module\PGClient\Services\Sender;
use PGI\Module\PGFramework\Services\Handlers\RequirementHandler;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;
use Exception;

/**
 * Class ApiFacadeFactory
 * @package APICharity\Services\Factories
 */
class ApiFacadeFactory
{
    /** @var RequirementHandler */
    private $requirementHandler;

    /** @var Logger */
    private $logger;

    /** @var Settings */
    private $settings;

    /** @var ParametersComponent */
    private $parameters;

    public function __construct(
        RequirementHandler $requirementHandler,
        Logger $logger,
        Settings $settings,
        ParametersComponent $parameters
    ) {
        $this->requirementHandler = $requirementHandler;
        $this->logger = $logger;
        $this->settings = $settings;
        $this->parameters = $parameters;
    }

    /**
     * @return ApiFacade
     * @throws Exception
     */
    public function build()
    {
        return new ApiFacade(
            $this->getRequestSender(),
            $this->getRequestFactory(),
            $this->settings,
            $this->requirementHandler
        );
    }

    /**
     * @return RequestFactory
     * @throws Exception
     */
    public function getRequestFactory()
    {
        $access_token = $this->settings->get('charity_access_token');
        $refresh_token = $this->settings->get('charity_refresh_token');
        $protocol = $this->settings->get('charity_use_https') ? 'https' : 'http';
        $apiServer = $this->computeApiServer();

        $host = "$protocol://$apiServer";

        $sharedHeaders = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Cache-Control: no-cache"
        );

        if (!empty($access_token)) {
            $sharedHeaders[] = "Authorization: Bearer $access_token";
        }

        $sharedParameters = array(
            'refresh_token' => $refresh_token,
            'host' => $host
        );

        return new RequestFactory(
            $this->parameters['api.charity.requests'],
            $sharedHeaders,
            $sharedParameters
        );
    }

    protected function getRequestSender()
    {
        /** @var Sender $requestSender */
        $requestSender = new Sender($this->logger);

        $requestSender
            ->addRequesters(new CurlRequester(
                $this->settings,
                $this->logger,
                $this->parameters['api.charity.clients.curl']
            ))
            ->addRequesters(new FopenRequester(
                $this->settings,
                $this->logger,
                $this->parameters['api.charity.clients.fopen']
            ))
            ->setResponseFactory(new ResponseFactory(
                $this->logger,
                $this->parameters['api.charity.requests'],
                $this->parameters['api.charity.responses'],
                $this->parameters['http_codes']
            ))
        ;

        return $requestSender;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function computeApiServer()
    {
        $server = $this->settings->get('charity_api_server');
        $url = $this->parameters["urls.charitykit.$server"];

        if (!$url) {
            $this->logger->error("Invalid url for charitykit api server. Use default value.");

            $server = $this->settings->getDefault('charity_api_server');
            $url = $this->parameters["urls.climatekit.$server"];
        }

        if (!$url) {
            throw new Exception("Invalid url for charity api server.");
        }

        return $url;
    }
}
