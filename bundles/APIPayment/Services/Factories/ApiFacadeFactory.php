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
 * @version   2.6.1
 *
 */

namespace PGI\Module\APIPayment\Services\Factories;

use PGI\Module\APIPayment\Services\Facades\ApiFacade;
use PGI\Module\PGClient\Services\Factories\RequestFactory;
use PGI\Module\PGClient\Services\Factories\ResponseFactory;
use PGI\Module\PGClient\Services\Requesters\CurlRequester;
use PGI\Module\PGClient\Services\Requesters\FopenRequester;
use PGI\Module\PGClient\Services\Sender;
use PGI\Module\PGFramework\Interfaces\Factories\ApiFactoryInterface;
use PGI\Module\PGModule\Interfaces\ApplicationFacadeInterface;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;

/**
 * Class ApiFacadeFactory
 * @package APIPayment\Services\Factories
 */
class ApiFacadeFactory implements ApiFactoryInterface
{
    /** @var LoggerInterface */
    private $logger;

    /** @var Settings */
    private $settings;

    /** @var ApplicationFacadeInterface */
    private $applicationFacade;

    /** @var ParametersComponent */
    private $parameters;

    public function __construct(
        LoggerInterface $logger,
        Settings $settings,
        ApplicationFacadeInterface $applicationFacade,
        ParametersComponent $parameters
    ) {
        $this->logger = $logger;
        $this->settings = $settings;
        $this->applicationFacade = $applicationFacade;
        $this->parameters = $parameters;
    }

    public function buildApiFacade()
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
                $this->parameters['api.payment.clients.curl']
            ))
            ->addRequesters(new FopenRequester(
                $this->settings,
                $this->logger,
                $this->parameters['api.payment.clients.fopen']
            ))
            ->setResponseFactory(new ResponseFactory(
                $this->logger,
                $this->parameters['api.payment.requests'],
                $this->parameters['api.payment.responses'],
                $this->parameters['http_codes']
            ))
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

        return new RequestFactory($this->parameters['api.payment.requests'], $sharedHeaders, $sharedParameters);
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
