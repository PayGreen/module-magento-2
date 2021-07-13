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
 * Class PGTreeServicesManagersCarbonData
 * @package PGTree\Services\Managers
 * @method PGTreeInterfacesRepositoriesCarbonData getRepository()
 */
class PGTreeServicesManagersCarbonData extends PGDatabaseFoundationsManager
{
    /**
     * @param $id
     * @return PGTreeInterfacesEntitiesCarbonData
     * @throws Exception
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    /**
     * @param string $pid
     * @return PGTreeInterfacesEntitiesCarbonData|null
     */
    public function getByPid($pid)
    {
        return $this->getRepository()->findByPid($pid);
    }

    /**
     * @param $id_order
     * @return PGTreeInterfacesEntitiesCarbonData|null
     */
    public function getByOrderPrimary($id_order)
    {
        return $this->getRepository()->findByOrderPrimary($id_order);
    }

    /**
     * @param string $id_fingerprint
     * @param float $footprint
     * @param float $carbon_offset
     * @return PGTreeInterfacesEntitiesCarbonData
     */
    public function create($id_fingerprint, $footprint, $carbon_offset)
    {
        return $this->getRepository()->create($id_fingerprint, $footprint, $carbon_offset);
    }

    /**
     * @param PGTreeInterfacesEntitiesCarbonData $carbonData
     * @return bool
     */
    public function save(PGTreeInterfacesEntitiesCarbonData $carbonData)
    {
        if ($carbonData->id() > 0) {
            return $this->getRepository()->update($carbonData);
        } else {
            return $this->getRepository()->insert($carbonData);
        }
    }

    /**
     * @param string $columnName
     * @return float
     */
    public function getSumOfTheDay($columnName)
    {
        return $this->getRepository()->getColumnSumByDayInterval($columnName);
    }

    /**
     * @param string $columnName
     * @return float
     */
    public function getSumOfTheWeek($columnName)
    {
        $dayIntervalBegin = (int) $this->initializeDatetime()->format('w');

        return $this->getRepository()->getColumnSumByDayInterval($columnName, $dayIntervalBegin);
    }

    /**
     * @param string $columnName
     * @return float
     */
    public function getSumOfTheMonth($columnName)
    {
        $dayIntervalBegin = (int) $this->initializeDatetime()->format('j');
        $dayIntervalBegin -= 1;

        return $this->getRepository()->getColumnSumByDayInterval($columnName, $dayIntervalBegin);
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