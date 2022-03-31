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

namespace PGI\Module\PGServer\Services\Factories;

use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGServer\Components\Trigger as TriggerComponent;
use PGI\Module\PGServer\Foundations\AbstractAcceptor;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use Exception;

/**
 * Class TriggerFactory
 * @package PGServer\Services\Factories
 */
class TriggerFactory extends AbstractObject
{
    /** @var AbstractAcceptor[] */
    private $acceptors = array();

    /** @var AggregatorComponent */
    private $acceptorAggregator;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(AggregatorComponent $acceptorAggregator, LoggerInterface $logger)
    {
        $this->acceptorAggregator = $acceptorAggregator;
        $this->logger = $logger;
    }

    /**
     * @param array $config
     * @return TriggerComponent
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
     * @return AbstractAcceptor
     * @throws Exception
     */
    protected function getAcceptor($code)
    {
        /** @var AbstractAcceptor $acceptor */
        $acceptor = null;

        if (array_key_exists($code, $this->acceptors)) {
            $acceptor = $this->acceptors[$code];
        } else {
            $acceptor = $this->acceptorAggregator->getService($code);

            $this->acceptors[$code] = $acceptor;
        }

        return $acceptor;
    }
}
