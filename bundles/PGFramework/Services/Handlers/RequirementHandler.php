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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGFramework\Services\Handlers;

use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use PGI\Module\PGModule\Services\Logger;
use Exception;
use PGI\Module\PGSystem\Components\Bag;

/**
 * Class RequirementHandler
 * @package PGFramework\Services\Handlers
 */
class RequirementHandler
{
    /** @var AggregatorComponent */
    private $requirementAggregator;

    private $requirements = array();

    /** @var Logger */
    private $logger;

    public function __construct(AggregatorComponent $requirementAggregator, array $requirements, Logger $logger)
    {
        $this->requirementAggregator = $requirementAggregator;
        $this->requirements = new Bag($requirements);
        $this->logger = $logger;
    }

    /**
     * @param string $name
     * @param mixed|null $arguments
     * @return bool
     * @throws Exception
     */
    public function isFulfilled($name, $arguments = null)
    {
        $isRequired = ($arguments === null) || (bool) $arguments;

        return ($this->isRequirementValid($name) === $isRequired);
    }

    /**
     * @param string $name
     * @return bool
     * @throws Exception
     */
    public function isRequirementValid($name)
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

        $requirementServiceName = $this->requirements["$name.name"];

        if ($requirementServiceName === null) {
            $requirementServiceName = $name;
        }

        $requirement = $this->requirementAggregator->getService($requirementServiceName);

        try {
            return $requirement->isValid();
        } catch (Exception $exception) {
            $this->logger->error("Requirement error during process: " . $exception->getMessage(), $exception);

            return false;
        }
    }

    /**
     * @param array $requirements
     * @return bool
     * @throws Exception
     */
    public function areFulfilled(array $requirements)
    {
        $result = true;

        foreach ($requirements as $name => $arguments) {
            if (!$this->isFulfilled($name, $arguments)) {
                $result = false;
                break;
            }
        }

        return $result;
    }
}
