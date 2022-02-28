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

namespace PGI\Module\PGPayment\Services\Repositories;

use PGI\Module\PGDatabase\Foundations\AbstractRepositoryDatabase;
use PGI\Module\PGPayment\Interfaces\Entities\TransactionEntityInterface;
use PGI\Module\PGPayment\Interfaces\Repositories\TransactionRepositoryInterface;
use PGI\Module\PGShop\Tools\Price as PriceTool;
use DateTime;
use Exception;

/**
 * Class TransactionRepository
 * @package PGPayment\Services\Repositories
 */
class TransactionRepository extends AbstractRepositoryDatabase implements TransactionRepositoryInterface
{
    const NB_SECONDS_IN_A_DAY = 86400;

    /**
     * @inheritdoc
     * @return TransactionEntityInterface|null
     * @throws Exception
     */
    public function findByPid($pid)
    {
        /** @var TransactionEntityInterface $result */
        $result = $this->findOneEntity("`pid` = '$pid'");

        return $result;
    }

    /**
     * @inheritdoc
     * @return TransactionEntityInterface|null
     * @throws Exception
     */
    public function findByOrderPrimary($id_order)
    {
        /** @var TransactionEntityInterface $result */
        $result = $this->findOneEntity("`id_order` = '$id_order'");

        return $result;
    }

    /**
     * @inheritDoc
     * @return TransactionEntityInterface
     * @throws Exception
     */
    public function create()
    {
        /** @var TransactionEntityInterface $result */
        $result = $this->wrapEntity();

        return $result;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function insert(TransactionEntityInterface $transaction)
    {
        return $this->insertEntity($transaction);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function update(TransactionEntityInterface $transaction)
    {
        return $this->updateEntity($transaction);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(TransactionEntityInterface $transaction)
    {
        return $this->deleteEntity($transaction);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function countByOrderPrimary($id_order)
    {
        $id_order = $this->getRequester()->quote($id_order);

        $sql = "SELECT COUNT(*) FROM  `%{database.entities.transaction.table}` WHERE `id_order` = '$id_order';";

        return (int) $this->getRequester()->fetchValue($sql);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function findAllByDayInterval($dayIntervalBegin = 0, $dayIntervalEnd = 1)
    {
        $where = $this->buildWhereConditionByDayInterval($dayIntervalBegin, $dayIntervalEnd);

        return $this->findAllEntities($where);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getCountByDayInterval($dayIntervalBegin = 0, $dayIntervalEnd = 1)
    {
        $where = $this->buildWhereConditionByDayInterval($dayIntervalBegin, $dayIntervalEnd);

        $sql = "SELECT COUNT(*)
            FROM `{$this->getTableName()}`
            WHERE {$where}";

        return (int) $this->getRequester()->fetchValue($sql);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getAmountByDayInterval($dayIntervalBegin = 0, $dayIntervalEnd = 1)
    {
        $where = $this->buildWhereConditionByDayInterval($dayIntervalBegin, $dayIntervalEnd);

        $sql = "SELECT SUM(`amount`)
            FROM `{$this->getTableName()}`
            WHERE {$where}";

        $amount = $this->getRequester()->fetchValue($sql);

        return PriceTool::toFloat($amount);
    }

    /**
     * @param int $dayIntervalBegin
     * @param int $dayIntervalEnd
     * @return string
     */
    private function buildWhereConditionByDayInterval($dayIntervalBegin = 0, $dayIntervalEnd = 1)
    {
        $timestamp = $this->initializeDatetime()->getTimestamp();

        $dayIntervalBegin *= self::NB_SECONDS_IN_A_DAY;
        $dayIntervalEnd *= self::NB_SECONDS_IN_A_DAY;

        return "`created_at` >= ({$timestamp} - {$dayIntervalBegin})
            AND `created_at` < ({$timestamp} + {$dayIntervalEnd});";
    }

    /**
     * @return DateTime
     */
    private function initializeDatetime()
    {
        return new DateTime();
    }
}
