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
 * @version   2.1.1
 *
 */

/**
 * Class PGPaymentServicesManagersTransactionManager
 *
 * @package PGPayment\Services\Managers
 * @method PGPaymentInterfacesRepositoriesTransactionRepositoryInterface getRepository()
 */
class PGPaymentServicesManagersTransactionManager extends PGDatabaseFoundationsManager
{
    /**
     * @param $id
     * @return PGPaymentInterfacesEntitiesTransactionInterface
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    /**
     * @param string $pid
     * @return PGPaymentInterfacesEntitiesTransactionInterface|null
     */
    public function getByPid($pid)
    {
        return $this->getRepository()->findByPid($pid);
    }

    public function getByOrderPrimary($id_order)
    {
        return $this->getRepository()->findByOrderPrimary($id_order);
    }

    /**
     * @param string $pid
     * @param PGShopInterfacesEntitiesOrder $order
     * @param string $state
     * @param string $mode
     * @param int $amount
     * @return PGPaymentInterfacesEntitiesTransactionInterface
     * @throws Exception
     */
    public function create($pid, PGShopInterfacesEntitiesOrder $order, $state, $mode, $amount)
    {
        /** @var PGPaymentInterfacesEntitiesTransactionInterface $transaction */
        $transaction = $this->getRepository()->create();

        $transaction
            ->setPid($pid)
            ->setOrder($order)
            ->setOrderState($state)
            ->setMode($mode)
            ->setAmount($amount)
            ->setCreatedAt(new DateTime())
        ;

        return $transaction;
    }

    public function save(PGPaymentInterfacesEntitiesTransactionInterface $transaction)
    {
        if ($transaction->id() > 0) {
            return $this->getRepository()->update($transaction);
        } else {
            return $this->getRepository()->insert($transaction);
        }
    }

    public function delete(PGPaymentInterfacesEntitiesTransactionInterface $transaction)
    {
        return $this->getRepository()->delete($transaction);
    }

    /**
     * Check if an order was payed with PayGreen
     * @param int $id_order
     * @return bool
     */
    public function hasTransaction($id_order)
    {
        $count = $this->getRepository()->countByOrderPrimary($id_order);

        return ($count > 0);
    }

    /**
     * @param string $pid
     * @param string $state
     * @return bool
     * @throws Exception
     */
    public function updateTransaction($pid, $state)
    {
        /** @var PGPaymentInterfacesEntitiesTransactionInterface $transaction */
        $transaction = $this->getByPid($pid);

        if ($transaction === null) {
            throw new Exception("Transaction with PID '$pid' not found.");
        }

        $transaction->setOrderState($state);

        return $this->save($transaction);
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getCountOfTheDay()
    {
        return $this->getRepository()->getCountByDayInterval();
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getAmountOfTheDay()
    {
        return $this->getRepository()->getAmountByDayInterval();
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getCountOfTheWeek()
    {
        $dayIntervalBegin = (int) $this->initializeDatetime()->format('w') - 1;

        return $this->getRepository()->getCountByDayInterval($dayIntervalBegin);
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getAmountOfTheWeek()
    {
        $dayIntervalBegin = (int) $this->initializeDatetime()->format('w') - 1;

        return $this->getRepository()->getAmountByDayInterval($dayIntervalBegin);
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getCountOfTheMonth()
    {
        $dayIntervalBegin = (int) $this->initializeDatetime()->format('j') - 1;

        return $this->getRepository()->getCountByDayInterval($dayIntervalBegin);
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getAmountOfTheMonth()
    {
        $dayIntervalBegin = (int) $this->initializeDatetime()->format('j') - 1;

        return $this->getRepository()->getAmountByDayInterval($dayIntervalBegin);
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getGrowthOfTheMonth()
    {
        $dayIntervalBegin = (int) $this->initializeDatetime()->format('j') - 1;
        $numberOfDaysLastMonth = $this->getNumberOfDaysLastMonth();
        $newvalue = $this->getRepository()->getAmountByDayInterval($dayIntervalBegin);
        $previousvalue = $this->getRepository()->getAmountByDayInterval($dayIntervalBegin+$numberOfDaysLastMonth,-$numberOfDaysLastMonth);

        return $this->getGrowth($newvalue,$previousvalue);
    }

    /**
     * @param int $currentMonthDay
     * @return array
     * @throws Exception
     */
    public function getCountsForTheCurrentMonth($currentMonthDay)
    {
        $transactions = $this->getRepository()->findAllByDayInterval($currentMonthDay);

        return $this->getCountsForTransactions($transactions);
    }

    /**
     * @param int $currentMonthDay
     * @return array
     * @throws Exception
     */
    public function getCountsForTheLastMonth($currentMonthDay)
    {
        $numberOfDaysLastMonth = $this->getNumberOfDaysLastMonth();
        $transactions = $this->getRepository()->findAllByDayInterval($currentMonthDay+$numberOfDaysLastMonth,-$currentMonthDay);

        return $this->getCountsForTransactions($transactions);
    }

    /**
     * @param array $transactions
     * @return array
     * @throws Exception
     */
    public function getCountsForTransactions($transactions)
    {
        $counts = array();

        /** @var PGPaymentInterfacesEntitiesTransactionInterface $transaction */
        foreach ($transactions as $transaction) {
            $createdAt = $transaction->getCreatedAt()->getTimestamp();
            $datetime = new Datetime();
            $transactionDay = $datetime->setTimestamp($createdAt)->setTime(0, 0)->format('d/m');

            if (array_key_exists($transactionDay, $counts)) {
                $counts[$transactionDay] += 1;
            } else {
                $counts[$transactionDay] = 1;
            }
        }

        $this->sortTransactionsByDate($counts);

        return $counts;
    }

    /**
     * @param int $currentMonthDay
     * @return array
     * @throws Exception
     */
    public function getAmountsForTheCurrentMonth($currentMonthDay)
    {
        $transactions = $this->getRepository()->findAllByDayInterval($currentMonthDay);

        return $this->getAmountsForTransactions($transactions);
    }

    /**
     * @param int $currentMonthDay
     * @return array
     * @throws Exception
     */
    public function getAmountsForTheLastMonth($currentMonthDay)
    {
        $numberOfDaysLastMonth = $this->getNumberOfDaysLastMonth();
        $transactions = $this->getRepository()->findAllByDayInterval($currentMonthDay+$numberOfDaysLastMonth,-$currentMonthDay);

        return $this->getAmountsForTransactions($transactions);

    }

    /**
     * @param array $transactions
     * @return array
     * @throws Exception
     */
    protected function getAmountsForTransactions($transactions)
    {
         $amounts = array();

        /** @var PGPaymentInterfacesEntitiesTransactionInterface $transaction */
        foreach ($transactions as $transaction) {
            $createdAt = $transaction->getCreatedAt()->getTimestamp();
            $datetime = new Datetime();
            $transactionDay = $datetime->setTimestamp($createdAt)->setTime(0, 0)->format('d/m');
            $transactionAmount = PGShopToolsPrice::toFloat($transaction->getAmount());

            if (array_key_exists($transactionDay, $amounts)) {
                $amounts[$transactionDay] += $transactionAmount;
            } else {
                $amounts[$transactionDay] = $transactionAmount;
            }
        }

        $this->sortTransactionsByDate($amounts);

        return $amounts;
    }



    private function sortTransactionsByDate($transactions)
    {
        uksort($transactions, function ($dateA, $dateB) {
            $dayA = (int) substr($dateA, 0, 2);
            $monthA = (int) substr($dateA, 3, 2);

            $dayB = (int) substr($dateB, 0, 2);
            $monthB = (int) substr($dateB, 3, 2);

            if ($monthB > $monthA) {
                return -1;
            } elseif ($monthB === $monthA) {
                if ($dayB > $dayA) {
                    return -1;
                } else {
                    return 1;
                }
            } else {
                return 1;
            }
        });
    }

    /**
     * @return DateTime
     */
    private function initializeDatetime()
    {
        $datetime = new DateTime();
        $datetime->setTime(0, 0);

        return $datetime;
    }
    /**
     * @param int $newvalue
     * @param int $previousvalue
     * @return float
     * @throws Exception
     */
    public function getGrowth($newvalue, $previousvalue)
    {
        if($previousvalue == 0 || $newvalue == 0) {
            return 0;
        }
        $increase = $newvalue - $previousvalue;
        $result = $increase / $previousvalue;

        return round($result*100,2);
    }

    /**
     * @throws Exception
     */
    public function getNumberOfDaysLastMonth()
    {
        return date("t", mktime(0,0,0, date("n") - 1));
    }

}
