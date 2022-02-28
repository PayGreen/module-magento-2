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
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGFramework\Services\Handlers;

use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use PGI\Module\PGFramework\Interfaces\RequirementInterface;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use Exception;
use PGI\Module\PGSystem\Components\Bag;
use PGI\Module\PGSystem\Components\Parser;
use PGI\Module\PGSystem\Exceptions\Configuration;
use PGI\Module\PGSystem\Interfaces\Services\ConfigurableServiceInterface;

/**
 * Class RequirementHandler
 * @package PGFramework\Services\Handlers
 */
class RequirementHandler
{
    /** @var AggregatorComponent */
    private $requirementAggregator;

    /** @var Parser */
    private $parser;

    private $requirements = array();

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        AggregatorComponent $requirementAggregator,
        Parser $parser,
        array $requirements,
        LoggerInterface $logger
    ) {
        $this->requirementAggregator = $requirementAggregator;
        $this->parser = $parser;
        $this->requirements = new Bag($requirements);
        $this->logger = $logger;
    }

    /**
     * @param string $name
     * @param mixed|null $arguments
     * @return bool
     * @throws Exception
     */
    public function isFulfilled($name)
    {
        $isRequired = true;

        if (substr($name, 0, 1) === '!') {
            $isRequired = false;
            $name = substr($name, 1);
        }

        return ($this->isRequirementValid($name) === $isRequired);
    }

    /**
     * @param string $name
     * @return bool
     * @throws Exception
     */
    protected function isRequirementValid($name)
    {
        if (!isset($this->requirements[$name])) {
            throw new Exception("Undefined requirements '$name'.");
        }

        $requirementParents = $this->requirements["$name.requirements"];

        if (is_array($requirementParents)) {
            foreach ($requirementParents as $requirementParent) {
                if (!$this->isFulfilled($requirementParent)) {
                    return false;
                }
            }
        }

        $requirement = $this->buildRequirement($name);

        try {
            $result = $requirement->isValid();
        } catch (Exception $exception) {
            $result = false;
            $this->logger->error("Requirement error during process: " . $exception->getMessage(), $exception);
        }

        return $result;
    }

    /**
     * @param string $name
     * @return RequirementInterface
     * @throws Exception
     */
    protected function buildRequirement($name)
    {
        $requirementServiceName = $this->requirements["$name.name"];

        if ($requirementServiceName === null) {
            $requirementServiceName = $name;
        }

        /** @var RequirementInterface $requirement */
        $requirement = $this->requirementAggregator->getService($requirementServiceName);

        /** @var array|null $config */
        $config = $this->requirements["$name.config"];

        if (is_array($config)) {
            if ($requirement instanceof ConfigurableServiceInterface) {
                $config = $this->parser->parseConfig($config);
                $requirement->addConfig($config);
            } else {
                throw new Configuration("Requirement with 'config' parameter but without ConfigurableServiceInterface.");
            }
        }

        return $requirement;
    }

    /**
     * @param array $requirements
     * @return bool
     * @throws Exception
     */
    public function areFulfilled(array $requirements)
    {
        $result = true;

        foreach ($requirements as $name) {
            if (!$this->isFulfilled($name)) {
                $result = false;
                break;
            }
        }

        return $result;
    }
}
