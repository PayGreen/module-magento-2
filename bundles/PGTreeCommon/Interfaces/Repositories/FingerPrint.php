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
 * @version   2.0.0
 *
 */

/**
 * Interface PGTreeCommonInterfacesRepositoriesFingerPrint
 * @package PGTreeCommon\Interfaces\Repositories
 */
interface PGTreeCommonInterfacesRepositoriesFingerPrint extends PGDatabaseInterfacesRepository
{
    /**
     * @param string $session
     * @return PGTreeCommonInterfacesEntitiesFingerPrint|null
     */
    public function findBySession($session);

    /**
     * @param string $session
     * @param string $browser
     * @param string $device
     * @return PGTreeCommonInterfacesEntitiesFingerPrint
     */
    public function create($session, $browser, $device);

    /**
     * @param PGTreeCommonInterfacesEntitiesFingerPrint $fingerprint
     * @return bool
     */
    public function insert(PGTreeCommonInterfacesEntitiesFingerPrint $fingerprint);

    /**
     * @param PGTreeCommonInterfacesEntitiesFingerPrint $fingerprint
     * @return bool
     */
    public function update(PGTreeCommonInterfacesEntitiesFingerPrint $fingerprint);

    /**
     * @param PGTreeCommonInterfacesEntitiesFingerPrint $fingerprint
     * @return bool
     */
    public function delete(PGTreeCommonInterfacesEntitiesFingerPrint $fingerprint);
}
