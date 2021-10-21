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
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGCharity\Services\Repositories;

use DateTime;
use Exception;
use PGI\Module\PGCharity\Interfaces\Entities\GiftEntityInterface;
use PGI\Module\PGCharity\Interfaces\Repositories\GiftRepositoryInterface;
use PGI\Module\PGDatabase\Foundations\AbstractRepositoryDatabase;
use PGI\Module\PGDatabase\Interfaces\EntityPersistedInterface;
use PGI\Module\PGShop\Tools\Price as PriceTool;

class GiftRepository extends AbstractRepositoryDatabase implements GiftRepositoryInterface
{
    const NB_SECONDS_IN_A_DAY = 86400;

    /**
     * @inheritdoc
     * @return EntityPersistedInterface|null
     * @throws Exception
     */
    public function findByOrderPrimary($id_order)
    {
        return $this->findOneEntity("`id_order` = '$id_order'");
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function create($reference, $id_order, $id_partnership, $amount)
    {
        $createdAt = new DateTime();

        return $this->wrapEntity(array(
            'reference' => $reference,
            'id_order' => $id_order,
            'id_partnership' => $id_partnership,
            'amount' => $amount,
            'created_at' => $createdAt->getTimestamp()
        ));
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function insert(GiftEntityInterface $gift)
    {
        return $this->insertEntity($gift);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function update(GiftEntityInterface $gift)
    {
        return $this->updateEntity($gift);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(GiftEntityInterface $gift)
    {
        return $this->deleteEntity($gift);
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
        $datetime = new DateTime();
        $datetime->setTime(0, 0);

        return $datetime;
    }
}
