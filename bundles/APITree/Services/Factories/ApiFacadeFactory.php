<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.5.2
 *
 */

namespace PGI\Module\APITree\Services\Factories;

use PGI\Module\APITree\Services\Facades\ApiFacade;
use PGI\Module\PGClient\Services\Factories\RequestFactory;
use PGI\Module\PGClient\Services\Factories\ResponseFactory;
use PGI\Module\PGClient\Services\Requesters\CurlRequester;
use PGI\Module\PGClient\Services\Requesters\FopenRequester;
use PGI\Module\PGClient\Services\Sender;
use PGI\Module\PGFramework\Services\Handlers\RequirementHandler;
use PGI\Module\PGModule\Interfaces\ApplicationFacadeInterface;
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

    /** @var ApplicationFacadeInterface */
    private $applicationFacade;

    /** @var RequirementHandler */
    private $requirementHandler;

    public function __construct(
        Logger $logger,
        Settings $settings,
        ParametersComponent $parameters,
        ApplicationFacadeInterface $applicationFacade,
        RequirementHandler $requirementHandler
    ) {
        $this->logger = $logger;
        $this->settings = $settings;
        $this->parameters = $parameters;
        $this->applicationFacade = $applicationFacade;
        $this->requirementHandler = $requirementHandler;
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
        $access_token = $this->settings->get('tree_access_token');
        $refresh_token = $this->settings->get('tree_refresh_token');
        $protocol = $this->settings->get('tree_use_https') ? 'https' : 'http';
        $apiServer = $this->computeApiServer();

        $host = "$protocol://$apiServer";

        $sharedHeaders = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Cache-Control: no-cache",
            "User-Agent: " . $this->buildUserAgentHeader()
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
                $this->parameters['api.tree.responses'],
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

    protected function buildUserAgentHeader()
    {
        $application = $this->applicationFacade->getName();
        $applicationVersion = $this->applicationFacade->getVersion();
        $moduleType = ($this->isImpact()) ? 'module-impact' : 'module';
        $moduleVersion = PAYGREEN_MODULE_VERSION;

        if (defined('PHP_MAJOR_VERSION') && defined('PHP_MINOR_VERSION') && defined('PHP_RELEASE_VERSION')) {
            $phpVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION;
        } else {
            $phpVersion = phpversion();
        }

        return "$application/$applicationVersion php:$phpVersion;$moduleType:$moduleVersion";
    }

    /**
     * @return bool
     */
    protected function isImpact()
    {
        return ($this->parameters['module.name'] === 'Impact');
    }
}
