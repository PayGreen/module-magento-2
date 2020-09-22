<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.1.1
 */

/**
 * Class PGDomainEntitiesLock
 *
 * @package PGDomain\Entities
 */
class PGDomainEntitiesLock extends PGFrameworkFoundationsAbstractEntityPersisted implements PGDomainInterfacesEntitiesLockInterface
{
    /**
     * @inheritdoc
     */
    public function getPid()
    {
        return $this->get('pid');
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function getLockedAt()
    {
        $timestamp = (int) $this->get('locked_at');

        $dt = new DateTime();

        return $dt->setTimestamp($timestamp);
    }

    /**
     * @inheritdoc
     */
    public function setLockedAt(DateTime $lockedAt)
    {
        return $this->set('locked_at', $lockedAt->getTimestamp());
    }
}
