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

namespace PGI\Module\PGClient\Components;

/**
 * Class ValidityRangeList
 * @package PGClient\Components
 */
class ValidityRangeList
{
    /** @var array */
    private $ranges;

    /**
     * ValidityRangeList constructor.
     * @param $ranges
     */
    public function __construct($ranges)
    {
        $this->ranges = explode(',', $ranges);
        array_walk($this->ranges, 'trim');
    }

    public function isValid($code)
    {
        foreach($this->ranges as $range) {
            list($min, $max) = $this->explodeRange($range);

            if (($min <= $code) && ($code <= $max)) {
                return true;
            }
        }

        return false;
    }

    protected function explodeRange($range)
    {
        $explodedRange = explode('-', $range);

        if (count($explodedRange) > 1) {
            list($min, $max) = $explodedRange;

            if ($max === '*') {
                $max = ceil(((int) $min) / 100) + 99;
            }
        } else {
            $min = $range;
            $max = $range;
        }

        return array((int) $min, (int) $max);
    }
}
