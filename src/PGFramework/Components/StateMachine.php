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
 * @version   1.0.1
 */

/**
 * Class PGFrameworkComponentsStateMachine
 * @package PGFramework\Components
 */
class PGFrameworkComponentsStateMachine
{
    private $start = array();

    private $transitions = array();

    /**
     * PGFrameworkComponentsStateMachine constructor.
     * @param array $configuration
     * @throws Exception
     */
    public function __construct(array $configuration)
    {
        if (!array_key_exists('start', $configuration) || !is_array($configuration['start'])) {
            throw new Exception("Invalid or not found 'start' parameter.");
        }

        if (!array_key_exists('transitions', $configuration) || !is_array($configuration['transitions'])) {
            throw new Exception("Invalid or not found 'transitions' parameter.");
        }

        $this->start = $configuration['start'];
        $this->transitions = $configuration['transitions'];
    }

    /**
     * @param string $state
     * @return bool
     */
    public function isAllowedStart($state)
    {
        return in_array($state, $this->start);
    }

    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function isAllowedTransition($from, $to)
    {
        return (array_key_exists($from, $this->transitions) && in_array($to, $this->transitions[$from]));
    }
}
