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

namespace PGI\Module\PGShop\Services\Mappers;

use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGShop\Interfaces\OrderStateMapperStrategyInterface;
use PGI\Module\PGSystem\Exceptions\Configuration as ConfigurationException;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use LogicException;

/**
 * Class OrderStateMapper
 * @package PGShop\Services\Mappers
 */
class OrderStateMapper extends AbstractObject
{
    const STATE_UNKNOWN = 'UNKNOWN';

    /** @var OrderStateMapperStrategyInterface[] */
    private $mapperStrategies = array();

    /** @var array */
    protected $definitions;

    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    /**
     * @param OrderStateMapperStrategyInterface $mapperStrategy
     * @param string $name
     * @throws ConfigurationException
     */
    public function addMapperStrategy(OrderStateMapperStrategyInterface $mapperStrategy, $name)
    {
        if (!is_string($name) || empty($name)) {
            $strategyClassName = get_class($mapperStrategy);
            $message = "OrderState mapping strategy must be named : '$strategyClassName'.";
            throw new ConfigurationException($message);
        }

        $this->mapperStrategies[$name] = $mapperStrategy;

        $definitions = array();

        foreach ($this->definitions as $state => $definition) {
            if (!is_array($definition)) {
                $message = "Uncorrectly defined order state : '$state'.";
                throw new ConfigurationException($message);
            } elseif (array_key_exists('source', $definition)) {
                if (!is_array($definition['source'])) {
                    $message = "Uncorrectly sourced order state : '$state'.";
                    throw new ConfigurationException($message);
                } elseif (!array_key_exists('type', $definition['source'])) {
                    $message = "Undefined mapping strategy for order state : '$state'.";
                    throw new ConfigurationException($message);
                } elseif (!is_string($definition['source']['type'])) {
                    $message = "Uncorrectly defined mapping strategy for order state : '$state'.";
                    throw new ConfigurationException($message);
                }

                if ($definition['source']['type'] === $name) {
                    $definitions[$state] = $definition['source'];
                }
            }
        }

        $mapperStrategy->setDefinitions($definitions);
    }

    /**
     * @param array $source
     * @return string
     */
    public function getOrderState(array $source)
    {
        $state = self::STATE_UNKNOWN;

        /** @var OrderStateMapperStrategyInterface $mapperStrategy */
        foreach ($this->mapperStrategies as $mapperStrategy) {
            if ($mapperStrategy->isRecognizedLocalState($source)) {
                $state = $mapperStrategy->getState($source);
            }
        }

        if ($state === self::STATE_UNKNOWN) {
            /** @var Logger $logger */
            $logger = $this->getService('logger');

            $logger->warning("Unknown order state.", $source);
        }

        return $state;
    }

    /**
     * @param string $state
     * @return array
     * @throws ConfigurationException
     */
    public function getLocalOrderState($state)
    {
        if (!array_key_exists($state, $this->definitions)) {
            $message = "Undefined order state : '$state'.";
            throw new ConfigurationException($message);
        } elseif (!is_array($this->definitions[$state])) {
            $message = "Uncorrectly defined order state : '$state'.";
            throw new ConfigurationException($message);
        } elseif (!array_key_exists('source', $this->definitions[$state])) {
            $message = "Unsourced order state : '$state'.";
            throw new ConfigurationException($message);
        } elseif (!is_array($this->definitions[$state]['source'])) {
            $message = "Uncorrectly sourced order state : '$state'.";
            throw new ConfigurationException($message);
        } elseif (!array_key_exists('type', $this->definitions[$state]['source'])) {
            $message = "Undefined mapping strategy for order state : '$state'.";
            throw new ConfigurationException($message);
        } elseif (!is_string($this->definitions[$state]['source']['type'])) {
            $message = "Uncorrectly defined mapping strategy for order state : '$state'.";
            throw new ConfigurationException($message);
        } elseif (!array_key_exists($this->definitions[$state]['source']['type'], $this->mapperStrategies)) {
            $message = "Mapping strategy not found : '{$this->definitions[$state]['source']['type']}'.";
            throw new LogicException($message);
        }

        $strategy = $this->definitions[$state]['source']['type'];

        /** @var OrderStateMapperStrategyInterface $mapperStrategy */
        $mapperStrategy = $this->mapperStrategies[$strategy];

        return $mapperStrategy->getLocalState($state);
    }
}
