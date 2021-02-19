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
 * @version   1.2.3
 *
 */

/**
 * Interface PGDomainInterfacesRepositoriesFingerPrintRepositoryInterface
 * @package PGDomain\Interfaces\Repositories
 */
interface PGDomainInterfacesRepositoriesFingerPrintRepositoryInterface extends PGFrameworkInterfacesRepositoryInterface
{
    /**
     * @param string $session
     * @return PGDomainInterfacesEntitiesFingerPrintInterface|null
     */
    public function findBySession($session);

    /**
     * @param string $session
     * @param string $browser
     * @param string $device
     * @return PGDomainInterfacesEntitiesFingerPrintInterface
     */
    public function create($session, $browser, $device);

    /**
     * @param PGDomainInterfacesEntitiesFingerPrintInterface $fingerprint
     * @return bool
     */
    public function insert(PGDomainInterfacesEntitiesFingerPrintInterface $fingerprint);

    /**
     * @param PGDomainInterfacesEntitiesFingerPrintInterface $fingerprint
     * @return bool
     */
    public function update(PGDomainInterfacesEntitiesFingerPrintInterface $fingerprint);

    /**
     * @param PGDomainInterfacesEntitiesFingerPrintInterface $fingerprint
     * @return bool
     */
    public function delete(PGDomainInterfacesEntitiesFingerPrintInterface $fingerprint);
}
