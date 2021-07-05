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
 * Class PGFrameworkServicesHandlersRequirementHandler
 * @package PGFramework\Services\Handlers
 */
class PGFrameworkServicesHandlersRequirementHandler
{
    /** @var PGFrameworkComponentsAggregator */
    private $requirementAggregator;

    public function __construct(PGFrameworkComponentsAggregator $requirementAggregator)
    {
        $this->requirementAggregator = $requirementAggregator;
    }

    /**
     * @param string $name
     * @param mixed|null $arguments
     * @return bool
     * @throws Exception
     */
    public function isFulfilled($name, $arguments = null)
    {
        /** @var PGFrameworkInterfacesRequirementInterface $requirement */
        $requirement = $this->requirementAggregator->getService($name);

        return $requirement->isFulfilled($arguments);
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
