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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGServer\Services\Factories;

use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGServer\Components\Trigger as TriggerComponent;
use PGI\Module\PGServer\Foundations\AbstractAcceptor;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use PGI\Module\PGSystem\Services\Container;
use Exception;

/**
 * Class TriggerFactory
 * @package PGServer\Services\Factories
 */
class TriggerFactory extends AbstractObject
{
    /** @var AbstractAcceptor[] */
    private $acceptors = array();

    /** @var string[] */
    private $acceptorServiceNames = array();

    /** @var Container */
    private $container;

    /** @var Logger */
    private $logger;

    public function __construct(Container $container, Logger $logger)
    {
        $this->container = $container;
        $this->logger = $logger;
    }

    public function addAcceptorServiceName($serviceName, $code)
    {
        $this->acceptorServiceNames[$code] = $serviceName;
    }

    /**
     * @param array $config
     * @throws Exception
     */
    public function buildTrigger(array $config)
    {
        $trigger = new TriggerComponent();

        try {
            foreach ($config as $acceptorCode => $acceptorConfig) {
                try {
                    $trigger->addAcceptor($this->getAcceptor($acceptorCode), $acceptorConfig);
                } catch (Exception $exception) {
                    $this->logger->critical("Unable to retrieve acceptor '$acceptorCode'.");

                    throw $exception;
                }
            }
        } catch (Exception $exception) {
            $this->logger->critical("Unable to build trigger.", $config);

            throw $exception;
        }

        return $trigger;
    }

    /**
     * @param string $code
     * @return bool
     */
    protected function acceptorExists($code)
    {
        return array_key_exists($code, $this->acceptorServiceNames);
    }

    /**
     * @param string $code
     * @return AbstractAcceptor
     * @throws Exception
     */
    protected function getAcceptor($code)
    {
        /** @var AbstractAcceptor $acceptor */
        $acceptor = null;

        if (array_key_exists($code, $this->acceptors)) {
            $acceptor = $this->acceptors[$code];
        } elseif ($this->acceptorExists($code)) {
            $acceptor = $this->container->get($this->acceptorServiceNames[$code]);

            $this->acceptors[$code] = $acceptor;
        } else {
            throw new Exception("Unknown acceptor type : $code.");
        }

        return $acceptor;
    }
}
