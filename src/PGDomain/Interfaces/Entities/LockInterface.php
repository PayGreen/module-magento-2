<?php
/**
 * 2014 - 2019 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.2
 */

/**
 * Interface PGDomainInterfacesEntitiesLockInterface
 * @package PGDomain\Interfaces\Entities
 */
interface PGDomainInterfacesEntitiesLockInterface extends PGFrameworkInterfacesWrappedEntityInterface
{
    /**
     * @return int
     */
    public function id();

    /**
     * @return string
     */
    public function getPid();

    /**
     * @return DateTime
     */
    public function getLockedAt();

    /**
     * @param DateTime $lockedAt
     * @return self
     */
    public function setLockedAt(DateTime $lockedAt);
}
