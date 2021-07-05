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
 * @version   2.1.0
 *
 */

/**
 * Class APIPaymentServicesApiFactory
 * @package APIPayment\Services
 */
class APIPaymentServicesApiFactory implements PGFrameworkInterfacesApiFactoryInterface
{
    /** @var PGModuleServicesLogger */
    private $logger;

    /** @var PGModuleServicesSettings */
    private $settings;

    /** @var PGModuleInterfacesApplicationFacade */
    private $applicationFacade;

    /** @var PGSystemComponentsParameters */
    private $parameters;

    public function __construct(
        PGModuleServicesLogger $logger,
        PGModuleServicesSettings $settings,
        PGModuleInterfacesApplicationFacade $applicationFacade,
        PGSystemComponentsParameters $parameters
    ) {
        $this->logger = $logger;
        $this->settings = $settings;
        $this->applicationFacade = $applicationFacade;
        $this->parameters = $parameters;
    }

    public function buildApiFacade()
    {
        return new APIPaymentServicesApiFacade($this->getRequestSender(), $this->getRequestFactory());
    }

    protected function getRequestSender()
    {
        /** @var PGClientServicesRequestSender $requestSender */
        $requestSender = new PGClientServicesRequestSender($this->logger);

        $requestSender
            ->addRequesters(
                new PGClientServicesRequestersCurl($this->settings, $this->logger, $this->parameters['api.payment.clients.curl'])
            )
            ->addRequesters(
                new PGClientServicesRequestersFopen($this->settings, $this->logger, $this->parameters['api.payment.clients.fopen'])
            )
            ->setResponseFactory(
                new PGClientServicesResponseFactory($this->logger, $this->parameters['api.payment.requests'], $this->parameters['api.payment.responses'])
            )
        ;

        return $requestSender;
    }

    protected function getRequestFactory()
    {
        $public_key = $this->settings->get('public_key');
        $private_key = $this->settings->get('private_key');
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

        return new PGClientServicesRequestFactory($this->parameters['api.payment.requests'], $sharedHeaders, $sharedParameters);
    }

    protected function buildUserAgentHeader()
    {
        $application = $this->applicationFacade->getName();
        $applicationVersion = $this->applicationFacade->getVersion();
        $moduleVersion = PAYGREEN_MODULE_VERSION;

        if (defined('PHP_MAJOR_VERSION') && defined('PHP_MINOR_VERSION') && defined('PHP_RELEASE_VERSION')) {
            $phpVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION;
        } else {
            $phpVersion = phpversion();
        }

        return "$application/$applicationVersion php:$phpVersion;module:$moduleVersion";
    }
}
