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
 * @version   2.3.0
 *
 */

namespace PGI\Module\APITree\Services\Factories;

use PGI\Module\APITree\Services\Facades\ApiFacade;
use PGI\Module\PGClient\Services\Factories\RequestFactory;
use PGI\Module\PGClient\Services\Factories\ResponseFactory;
use PGI\Module\PGClient\Services\Requesters\CurlRequester;
use PGI\Module\PGClient\Services\Requesters\FopenRequester;
use PGI\Module\PGClient\Services\Sender;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;
use Exception;

/**
 * Class ApiFacadeFactory
 * @package APITree\Services\Factories
 */
class ApiFacadeFactory
{
    /** @var Logger */
    private $logger;

    /** @var Settings */
    private $settings;

    /** @var ParametersComponent */
    private $parameters;

    public function __construct(
        Logger $logger,
        Settings $settings,
        ParametersComponent $parameters
    ) {
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
        return new ApiFacade($this->getRequestSender(), $this->getRequestFactory());
    }

    protected function getRequestSender()
    {
        /** @var Sender $requestSender */
        $requestSender = new Sender($this->logger);

        $requestSender
            ->addRequesters(new CurlRequester(
                $this->settings,
                $this->logger,
                $this->parameters['api.tree.clients.curl']
            ))
            ->addRequesters(new FopenRequester(
                $this->settings,
                $this->logger,
                $this->parameters['api.tree.clients.fopen']
            ))
            ->setResponseFactory(new ResponseFactory(
                $this->logger,
                $this->parameters['api.tree.requests'],
                $this->parameters['api.tree.responses']
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
        $server = $this->settings->get('tree_api_server');
        $url = $this->parameters["urls.climatekit.$server"];

        if (!$url) {
            $this->logger->error("Invalid url for climatekit api server. Use default value.");

            $server = $this->settings->getDefault('tree_api_server');
            $url = $this->parameters["urls.climatekit.$server"];
        }

        if (!$url) {
            throw new Exception("Invalid url for climatekit api server.");
        }

        return $url;
    }

    /**
     * @return RequestFactory
     * @throws Exception
     */
    protected function getRequestFactory()
    {
        $access_token = $this->settings->get('tree_access_token');
        $refresh_token = $this->settings->get('tree_refresh_token');
        $protocol = $this->settings->get('tree_use_https') ? 'https' : 'http';
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
            $this->parameters['api.tree.requests'],
            $sharedHeaders,
            $sharedParameters
        );
    }
}
