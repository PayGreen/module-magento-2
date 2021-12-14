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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGCharity\Services\Managers;

use PGI\Module\PGDatabase\Foundations\AbstractManager;
use PGI\Module\PGCharity\Interfaces\Entities\GiftEntityInterface;
use PGI\Module\PGCharity\Interfaces\Repositories\GiftRepositoryInterface;
use DateTime;
use Exception;

/**
 * Class GiftManager
 * @package PGCharity\Services\Managers
 * @method GiftRepositoryInterface getRepository()
 */
class GiftManager extends AbstractManager
{
    /**
     * @param $id
     * @return GiftEntityInterface
     * @throws Exception
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    /**
     * @param $id_order
     * @return GiftEntityInterface|null
     */
    public function getByOrderPrimary($id_order)
    {
        return $this->getRepository()->findByOrderPrimary($id_order);
    }

    /**
     * @param string $reference
     * @param int $id_order
     * @param int $id_partnership
     * @param int $amount
     * @return GiftEntityInterface
     */
    public function create($reference, $id_order, $id_partnership, $amount)
    {
        return $this->getRepository()->create($reference, $id_order, $id_partnership, $amount);
    }

    /**
     * @param GiftEntityInterface $gift
     * @return bool
     */
    public function save(GiftEntityInterface $gift)
    {
        if ($gift->id() > 0) {
            return $this->getRepository()->update($gift);
        } else {
            return $this->getRepository()->insert($gift);
        }
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
     * @return int
     * @throws Exception
     */
    public function getCountOfTheLastHours()
    {
        return $this->getRepository()->getCountByDayInterval(1, 0);
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
     * @return float
     * @throws Exception
     */
    public function getAmountOfTheLastHours()
    {
        return $this->getRepository()->getAmountByDayInterval(1, 0);
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
     * @return int
     * @throws Exception
     */
    public function getCountOfTheLastSevenDays()
    {
        return $this->getRepository()->getCountByDayInterval(7, 0);
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
     * @return float
     * @throws Exception
     */
    public function getAmountOfTheLastSevenDays()
    {
        return $this->getRepository()->getAmountByDayInterval(7, 0);
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
     * @return int
     * @throws Exception
     */
    public function getCountOfTheLastThirtyDays()
    {
        return $this->getRepository()->getCountByDayInterval(30, 0);
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
    public function getAmountOfTheLastThirtyDays()
    {
        return $this->getRepository()->getAmountByDayInterval(30, 0);
    }

    /**
     * @return DateTime
     */
    private function initializeDatetime()
    {
        return new DateTime();
    }
}
