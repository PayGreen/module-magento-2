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

namespace PGI\Module\PGFramework\Components;

use Exception;

/**
 * Class StateMachine
 * @package PGFramework\Components
 */
class StateMachine
{
    private $start = array();

    private $transitions = array();

    /**
     * StateMachine constructor.
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
