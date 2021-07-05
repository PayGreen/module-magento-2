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
 * @version   2.1.0
 *
 */

/**
 * Interface PGTreeInterfacesRepositoriesCarbonData
 * @package PGTree\Interfaces\Repositories
 */
interface PGTreeInterfacesRepositoriesCarbonData extends PGDatabaseInterfacesRepository
{
    /**
     * @param int $id
     * @return PGTreeInterfacesEntitiesCarbonData|null
     */
    public function findByPrimary($id);

    /**
     * @param int $id_order
     * @return PGTreeInterfacesEntitiesCarbonData|null
     */
    public function findByOrderPrimary($id_order);

    /**
     * @param string $pid
     * @return PGTreeInterfacesEntitiesCarbonData|null
     */
    public function findByPid($pid);

    /**
     * @param string $id_fingerprint
     * @param float $footprint
     * @param float $carbon_offset
     * @return PGTreeInterfacesEntitiesCarbonData
     */
    public function create($id_fingerprint, $footprint, $carbon_offset);

    /**
     * @param PGTreeInterfacesEntitiesCarbonData $carbonData
     * @return bool
     */
    public function insert(PGTreeInterfacesEntitiesCarbonData $carbonData);

    /**
     * @param PGTreeInterfacesEntitiesCarbonData $carbonData
     * @return bool
     */
    public function update(PGTreeInterfacesEntitiesCarbonData $carbonData);

    /**
     * @param PGTreeInterfacesEntitiesCarbonData $carbonData
     * @return bool
     */
    public function delete(PGTreeInterfacesEntitiesCarbonData $carbonData);

    /**
     * @param int $dayIntervalBegin
     * @param int $dayIntervalEnd
     * @return array
     */
    public function findAllByDayInterval($dayIntervalBegin = 0, $dayIntervalEnd = 1);

    /**
     * @param string $columnName
     * @param int $dayIntervalBegin
     * @param int $dayIntervalEnd
     * @return float
     */
    public function getColumnSumByDayInterval($columnName, $dayIntervalBegin = 0, $dayIntervalEnd = 1);
}
