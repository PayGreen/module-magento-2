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
 * @version   1.2.4
 *
 */

class PGServerServicesHandlersAreaHandler
{
    private $areas = array();

    /** @var PGFrameworkServicesHandlersRequirementHandler */
    private $requirementHandler;

    public function __construct(array $areas)
    {
        $this->areas = $areas;
    }

    /**
     * @param string $route
     * @return string
     * @throws Exception
     */
    public function getRouteArea($route)
    {
        $selectedArea = null;

        foreach (array_keys($this->areas) as $area) {
            if ($this->isActionMatchingArea($route, $area)) {
                $selectedArea = $area;
            }
        }

        if ($selectedArea === null) {
            throw new Exception("Route '$route' not found in any area.");
        }

        return $selectedArea;
    }

    /**
     * @param string $action
     * @param string $area
     * @return bool
     * @throws Exception
     */
    protected function isActionMatchingArea($action, $area)
    {
        if (!array_key_exists('patterns', $this->areas[$area])) {
            throw new Exception("Area '$area' must contains 'patterns' key with all accepted route patterns.");
        }

        foreach ($this->areas[$area]['patterns'] as $pattern) {
            $pattern = str_replace('.', '\.', $pattern);
            $pattern = str_replace('*', '.*', $pattern);
            $pattern = "/^$pattern/";

            if (preg_match($pattern, $action)) {
                return true;
            }
        }

        return false;
    }
}
