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
 * @version   1.1.0
 */

/**
 * Class PGClientServicesApiFactory
 * @package PGClient\Services
 */
class PGClientServicesApiFactory implements PGFrameworkInterfacesApiFactoryInterface
{
    /** @var PGFrameworkServicesLogger */
    private $logger;

    /** @var PGFrameworkServicesSettings */
    private $settings;

    /** @var PGFrameworkInterfacesModuleFacadeInterface */
    private $moduleFacade;

    /** @var PGFrameworkComponentsParameters */
    private $parameters;

    public function __construct(
        PGFrameworkServicesLogger $logger,
        PGFrameworkServicesSettings $settings,
        PGFrameworkInterfacesModuleFacadeInterface $moduleFacade,
        PGFrameworkComponentsParameters $parameters
    ) {
        $this->logger = $logger;
        $this->settings = $settings;
        $this->moduleFacade = $moduleFacade;
        $this->parameters = $parameters;
    }

    public function buildApiFacade()
    {
        return new PGClientServicesApiFacade($this->getRequestSender(), $this->getRequestFactory());
    }

    protected function getRequestSender()
    {
        /** @var PGClientServicesRequestSender $requestSender */
        $requestSender = new PGClientServicesRequestSender($this->logger);

        $requestSender
            ->addRequesters(new PGClientServicesRequestersCurlRequester($this->settings, $this->logger, $this->parameters['client.curl']))
            ->addRequesters(new PGClientServicesRequestersFopenRequester($this->settings, $this->logger, $this->parameters['client.fopen']))
        ;

        return $requestSender;
    }

    protected function getRequestFactory()
    {
        list($public_key, $private_key) = $this->moduleFacade->getAPICredentials();

        $protocol = $this->settings->get('use_https') ? 'https' : 'http';
        $apiServer = $this->settings->get('api_server');

        if (strtoupper(substr($public_key, 0, 2)) === 'PP') {
            $public_key = substr($public_key, 2);
            $host = "$protocol://preprod.paygreen.fr";
        } elseif (strtoupper(substr($public_key, 0, 2)) === 'SB') {
            $public_key = substr($public_key, 2);
            $host = "$protocol://sandbox.paygreen.fr";
        } else {
            $host = "$protocol://$apiServer";
        }

        $sharedHeaders = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Cache-Control: no-cache",
            'User-Agent: ' . $this->buildUserAgentHeader()
        );

        if (!empty($private_key)) {
            $sharedHeaders[] = "Authorization: Bearer $private_key";
        }

        $sharedParameters = array(
            'ui' => $public_key,
            'host' => $host
        );

        return new PGClientServicesRequestFactory($this->parameters['api.requests'], $sharedHeaders, $sharedParameters);
    }

    protected function buildUserAgentHeader()
    {
        $application = $this->moduleFacade->getApplicationName();
        $applicationVersion = $this->moduleFacade->getApplicationVersion();
        $moduleVersion = PAYGREEN_MODULE_VERSION;

        if (defined('PHP_MAJOR_VERSION') && defined('PHP_MINOR_VERSION') && defined('PHP_RELEASE_VERSION')) {
            $phpVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION;
        } else {
            $phpVersion = phpversion();
        }

        return "$application/$applicationVersion php:$phpVersion;module:$moduleVersion";
    }
}
