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
 * Class APITreeServicesApiFactory
 * @package APITree\Services
 */
class APITreeServicesApiFactory implements PGFrameworkInterfacesApiFactoryInterface
{
    /** @var PGModuleServicesLogger */
    private $logger;

    /** @var PGModuleServicesSettings */
    private $settings;

    /** @var PGSystemComponentsParameters */
    private $parameters;

    public function __construct(
        PGModuleServicesLogger $logger,
        PGModuleServicesSettings $settings,
        PGSystemComponentsParameters $parameters
    ) {
        $this->logger = $logger;
        $this->settings = $settings;
        $this->parameters = $parameters;
    }

    public function buildApiFacade()
    {
        return new APITreeServicesApiFacade($this->getRequestSender(), $this->getRequestFactory());
    }

    protected function getRequestSender()
    {
        /** @var PGClientServicesRequestSender $requestSender */
        $requestSender = new PGClientServicesRequestSender($this->logger);

        $requestSender
            ->addRequesters(new PGClientServicesRequestersCurl(
                $this->settings,
                $this->logger,
                $this->parameters['tree_client.curl']
            ))
            ->addRequesters(new PGClientServicesRequestersFopen(
                $this->settings,
                $this->logger,
                $this->parameters['tree_client.fopen']
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
     * @return PGClientServicesRequestFactory
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

        return new PGClientServicesRequestFactory(
            $this->parameters['tree_api.requests'],
            $sharedHeaders,
            $sharedParameters
        );
    }
}
