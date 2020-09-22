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
 * @version   1.1.1
 */

class PGDomainServicesOrderStateMapper extends PGFrameworkFoundationsAbstractObject
{
    const STATE_UNKNOWN = 'UNKNOWN';

    /** @var PGDomainInterfacesOrderStateMapperStrategyInterface[] */
    private $mapperStrategies = array();

    /** @var array */
    protected $definitions;

    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    /**
     * @param PGDomainInterfacesOrderStateMapperStrategyInterface $mapperStrategy
     * @param string $name
     * @throws PGFrameworkExceptionsConfigurationException
     */
    public function addMapperStrategy(PGDomainInterfacesOrderStateMapperStrategyInterface $mapperStrategy, $name)
    {
        if (!is_string($name) || empty($name)) {
            $strategyClassName = get_class($mapperStrategy);
            $message = "OrderState mapping strategy must be named : '$strategyClassName'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        }

        $this->mapperStrategies[$name] = $mapperStrategy;

        $definitions = array();

        foreach ($this->definitions as $state => $definition) {
            if (!is_array($definition)) {
                $message = "Uncorrectly defined order state : '$state'.";
                throw new PGFrameworkExceptionsConfigurationException($message);
            } elseif (array_key_exists('source', $definition)) {
                if (!is_array($definition['source'])) {
                    $message = "Uncorrectly sourced order state : '$state'.";
                    throw new PGFrameworkExceptionsConfigurationException($message);
                } elseif (!array_key_exists('type', $definition['source'])) {
                    $message = "Undefined mapping strategy for order state : '$state'.";
                    throw new PGFrameworkExceptionsConfigurationException($message);
                } elseif (!is_string($definition['source']['type'])) {
                    $message = "Uncorrectly defined mapping strategy for order state : '$state'.";
                    throw new PGFrameworkExceptionsConfigurationException($message);
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

        /** @var PGDomainInterfacesOrderStateMapperStrategyInterface $mapperStrategy */
        foreach ($this->mapperStrategies as $mapperStrategy) {
            if ($mapperStrategy->isRecognizedLocalState($source)) {
                $state = $mapperStrategy->getState($source);
            }
        }

        if ($state === self::STATE_UNKNOWN) {
            /** @var PGFrameworkServicesLogger $logger */
            $logger = $this->getService('logger');

            $logger->warning("Unknown order state.", $source);
        }

        return $state;
    }

    /**
     * @param string $state
     * @return array
     * @throws PGFrameworkExceptionsConfigurationException
     */
    public function getLocalOrderState($state)
    {
        if (!array_key_exists($state, $this->definitions)) {
            $message = "Undefined order state : '$state'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!is_array($this->definitions[$state])) {
            $message = "Uncorrectly defined order state : '$state'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!array_key_exists('source', $this->definitions[$state])) {
            $message = "Unsourced order state : '$state'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!is_array($this->definitions[$state]['source'])) {
            $message = "Uncorrectly sourced order state : '$state'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!array_key_exists('type', $this->definitions[$state]['source'])) {
            $message = "Undefined mapping strategy for order state : '$state'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!is_string($this->definitions[$state]['source']['type'])) {
            $message = "Uncorrectly defined mapping strategy for order state : '$state'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!array_key_exists($this->definitions[$state]['source']['type'], $this->mapperStrategies)) {
            $message = "Mapping strategy not found : '{$this->definitions[$state]['source']['type']}'.";
            throw new LogicException($message);
        }

        $strategy = $this->definitions[$state]['source']['type'];

        /** @var PGDomainInterfacesOrderStateMapperStrategyInterface $mapperStrategy */
        $mapperStrategy = $this->mapperStrategies[$strategy];

        return $mapperStrategy->getLocalState($state);
    }
}
