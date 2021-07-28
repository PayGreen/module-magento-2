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

namespace PGI\Module\BOPayment\Services\Handlers;

use PGI\Module\PGPayment\Services\Managers\TransactionManager;
use DateTime;
use Exception;

/**
 * Class PaymentStatisticsHandler
 * @package BOPayment\Services\Handlers
 */
class PaymentStatisticsHandler
{
    /** @var TransactionManager */
    private $transactionManager;

    public function __construct(TransactionManager $transactionManager)
    {
        $this->transactionManager = $transactionManager;
    }

    /**
     * @throws Exception
     */
    public function getStatisticsData()
    {
        return array(
            'pgMonthDaysLabels' => json_encode(implode(';', $this->getTheLastMonthDaysLabels('d'))),
            'pgCurrentMonthDaysCounts' => json_encode(implode(';', $this->getTheCurrentMonthCounts())),
            'pgLastMonthDaysCounts' => json_encode(implode(';', $this->getTheLastMonthCounts())),
            'pgCurrentMonthDaysAmounts' => json_encode(implode(';', $this->getTheCurrentMonthAmounts())),
            'pgLastMonthDaysAmounts' => json_encode(implode(';', $this->getTheLastMonthAmounts()))
        );
    }

    /**
     * @return array
     */
    private function getTheCurrentMonthDaysLabels()
    {
        $currentMonthDay = $this->getNumberOfTheCurrentDay();
        $currentMonthDays = array();

        for ($i = ($currentMonthDay - 1); $i >= 0; $i--) {
            $currentMonthDays[] = date('d/m', strtotime("today - $i days"));
        }

        return $currentMonthDays;
    }

    /**
     * @param string $format
     * @return array
     */
    private function getTheLastMonthDaysLabels($format)
    {
        $lastMonthDays = array();

        for ($i = date('Y-m-d', strtotime('first day of last month')); $i <= date('Y-m-d', strtotime('last day of last month')); $i++) {
            $lastMonthDays[] = date($format, strtotime($i));
        }

        return $lastMonthDays;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getTheCurrentMonthAmounts()
    {
        $amounts = $this->transactionManager->getAmountsForTheCurrentMonth($this->getNumberOfTheCurrentDay());

        $results = array();

        $sum = 0;

        $labels = $this->getTheCurrentMonthDaysLabels();

        foreach ($labels as $label) {
            if (array_key_exists($label, $amounts)) {
                $sum += $amounts[$label];
            } else {
                $sum += 0;
            }

            $results[$label] = $sum;
        }

        return $results;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getTheLastMonthAmounts()
    {
        $currentMonthDay = $this->getNumberOfTheCurrentDay();
        $amounts = $this->transactionManager->getAmountsForTheLastMonth($currentMonthDay);

        $results = array();

        $sum = 0;

        $labels =  $this->getTheLastMonthDaysLabels('d/m');

        foreach ($labels as $label) {
            if (array_key_exists($label, $amounts)) {
                $sum += $amounts[$label];
            } else {
                $sum += 0;
            }

            $results[$label] = $sum;
        }

        return $results;
    }


    /**
     * @return array
     * @throws Exception
     */
    private function getTheCurrentMonthCounts()
    {
        $counts = array();

        $labels = $this->getTheCurrentMonthDaysLabels();

        foreach ($labels as $label) {
            $counts[$label] = 0;
        }

        return array_merge(
            $counts,
            $this->transactionManager->getCountsForTheCurrentMonth($this->getNumberOfTheCurrentDay())
        );
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getTheLastMonthCounts()
    {
        $counts = array();

        $labels = $this->getTheLastMonthDaysLabels('d/m');

        foreach ($labels as $label) {
            $counts[$label] = 0;
        }
        $currentMonthDay = $this->getNumberOfTheCurrentDay();
        return array_merge(
            $counts,
            $this->transactionManager->getCountsForTheLastMonth($currentMonthDay)
        );
    }

    /**
     * @return int
     */
    private function getNumberOfTheCurrentDay()
    {
        $datetime = new DateTime();
        return (int) $datetime->format('j');
    }
}
