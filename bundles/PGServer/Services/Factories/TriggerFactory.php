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
 * Class PGServerServicesFactoriesTriggerFactory
 * @package PGServer\Services\Factories
 */
class PGServerServicesFactoriesTriggerFactory extends PGSystemFoundationsObject
{
    /** @var PGServerFoundationsAbstractAcceptor[] */
    private $acceptors = array();

    /** @var string[] */
    private $acceptorServiceNames = array();

    /** @var PGSystemServicesContainer */
    private $container;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(PGSystemServicesContainer $container, PGModuleServicesLogger $logger)
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
        $trigger = new PGServerComponentsTrigger();

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
     * @return PGServerFoundationsAbstractAcceptor
     * @throws Exception
     */
    protected function getAcceptor($code)
    {
        /** @var PGServerFoundationsAbstractAcceptor $acceptor */
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
