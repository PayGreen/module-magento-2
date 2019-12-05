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
 * @version   0.3.5
 */

/**
 * Class PGClientServicesApiFactory
 * @package PGClient\Services
 */
class PGClientServicesApiFactory implements PGFrameworkInterfacesApiFactoryInterface
{
    /** @var PGFrameworkServicesLogger */
    private $logger;

    /** @var PGFrameworkInterfacesModuleFacadeInterface */
    private $moduleFacade;

    /** @var PGFrameworkComponentsParameters */
    private $parameters;

    public function __construct(
        PGFrameworkServicesLogger $logger,
        PGFrameworkInterfacesModuleFacadeInterface $moduleFacade,
        PGFrameworkComponentsParameters $parameters
    ) {
        $this->logger = $logger;
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
            ->addRequesters(new PGClientServicesRequestersCurlRequester($this->logger, $this->parameters['client.curl']))
            ->addRequesters(new PGClientServicesRequestersFopenRequester($this->logger, $this->parameters['client.fopen']))
        ;

        return $requestSender;
    }

    protected function getRequestFactory()
    {
        list($public_key, $private_key) = $this->moduleFacade->getAPICredentials();

        if (strtoupper(substr($public_key, 0, 2)) === 'PP') {
            $public_key = substr($public_key, 2);
            $host = 'https://preprod.paygreen.fr';
        } elseif (strtoupper(substr($public_key, 0, 2)) === 'SB') {
            $public_key = substr($public_key, 2);
            $host = 'https://sandbox.paygreen.fr';
        } elseif (PAYGREEN_ENV === 'DEV') {
            $host = 'https://preprod.paygreen.fr';
        } else {
            $host = 'https://paygreen.fr';
        }

        $sharedHeaders = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Cache-Control: no-cache",
            "Authorization: Bearer $private_key",
            'User-Agent: ' . $this->buildUserAgentHeader()
        );

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
        $phpVersion = phpversion();
        $moduleVersion = PAYGREEN_MODULE_VERSION;

        return "$application/$applicationVersion php:$phpVersion;module:$moduleVersion";
    }
}
