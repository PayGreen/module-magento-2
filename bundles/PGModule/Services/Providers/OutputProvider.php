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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGModule\Services\Providers;

use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use PGI\Module\PGFramework\Services\Handlers\RequirementHandler;
use PGI\Module\PGModule\Components\Output as OutputComponent;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGServer\Components\ResourceBag as ResourceBagComponent;
use PGI\Module\PGSystem\Components\Bag as BagComponent;
use Exception;

/**
 * Class OutputProvider
 * @package PGModule\Services\Providers
 */
class OutputProvider
{
    /** @var AggregatorComponent */
    private $outputBuilderAggregator;

    /** @var RequirementHandler */
    private $requirementHandler;

    /** @var Logger */
    private $logger;

    /** @var OutputComponent[] */
    private $zones = array();

    /** @var array */
    private $sources = array();

    /**
     * OutputProvider constructor.
     * @param AggregatorComponent $outputBuilderAggregator
     * @param RequirementHandler $requirementHandler
     * @param array $sources
     * @param Logger $logger
     */
    public function __construct(
        AggregatorComponent $outputBuilderAggregator,
        RequirementHandler $requirementHandler,
        array $sources,
        Logger $logger
    ) {
        $this->outputBuilderAggregator = $outputBuilderAggregator;
        $this->requirementHandler = $requirementHandler;
        $this->sources = $sources;
        $this->logger = $logger;
    }

    /**
     * @param array $names
     * @return ResourceBagComponent
     * @throws Exception
     */
    public function getResources(array $names)
    {
        $resources = new ResourceBagComponent();

        foreach ($names as $name) {
            $output = $this->getZoneOutput($name);

            $resources->merge($output->getResources());
        }

        return $resources;
    }

    /**
     * @param string $name
     * @param array $data
     * @return OutputComponent
     * @throws Exception
     */
    public function getZoneOutput($name, array $data = array())
    {
        if (!empty($data)) {
            $this->logger->debug("Buidling '$name' channel without cache.");

            return $this->buildZoneOutput($name, $data);
        }

        if (!array_key_exists($name, $this->zones)) {
            $this->zones[$name] = $this->buildZoneOutput($name);
        } else {
            $this->logger->debug("Channel '$name' already built.");
        }

        return $this->zones[$name];
    }

    /**
     * @param string $name
     * @param array $data
     * @return OutputComponent
     * @throws Exception
     */
    private function buildZoneOutput($name, array $data = array())
    {
        $this->logger->debug("Building channel output '$name'.");

        $zoneOutput = new OutputComponent();

        foreach ($this->sources as $source) {
            $config = new BagComponent($source);

            if ($this->isValidSource($config, $name)) {
                /** @var OutputComponent|null $output */
                $output = $this->buildOutput($config, $data);

                if ($output !== null) {
                    $zoneOutput->merge($output);
                }
            }
        }

        return $zoneOutput;
    }

    /**
     * @param BagComponent $config
     * @param string $name
     * @return bool
     * @throws Exception
     */
    private function isValidSource(BagComponent $config, $name)
    {
        $isValid = false;

        if (strtoupper($config['target']) === strtoupper($name)) {
            $isValid = true;

            if ($config['requirements']) {
                $isValid = $this->requirementHandler->areFulfilled($config['requirements']);
            }
        }

        return $isValid;
    }

    /**
     * @param BagComponent $config
     * @param array $data
     * @return OutputComponent|null
     * @throws Exception
     */
    private function buildOutput(BagComponent $config, array $data = array())
    {
        /** @var OutputComponent|null $output */
        $output = null;

        try {
            $output = $this->outputBuilderAggregator
                ->getService($config['builder'])
                ->build($data)
            ;
        } catch (Exception $exception) {
            $this->logger->error("An error occurred during channel building.", $exception);

            if (!$config['clean']) {
                throw $exception;
            } else {
                $this->logger->info("Cleaning channel building exception.");
            }
        }

        return $output;
    }
}
