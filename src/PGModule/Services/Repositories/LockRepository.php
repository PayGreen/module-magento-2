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
 */

use Paygreen\Payment\Model\TransactionLock;

class PGModuleServicesRepositoriesLockRepository extends PGModuleFoundationsAbstractMagentoRepository implements PGDomainInterfacesRepositoriesLockRepositoryInterface
{
    const ENTITY = 'Paygreen\Payment\Model\TransactionLock';
    const RESOURCE = 'Paygreen\Payment\Model\ResourceModel\TransactionLock';

    /**
     * @param TransactionLock $localEntity
     * @return PGModuleEntitiesLock
     */
    public function wrapEntity($localEntity)
    {
        return new PGModuleEntitiesLock($localEntity);
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function create($pid, DateTime $dateTime)
    {
        $dt = new DateTime();

        /** @var TransactionLock $lock */
        $lock = $this->createLocalEntity([
            'pid' => $pid,
            'locked_at' => $dt->getTimestamp()
        ]);

        $lock->save();

        return $this->wrapEntity($lock);
    }

    /**
     * @inheritdoc
     */
    public function findByPid($pid)
    {
        return $this->findByField('pid', $pid);
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function updateLock(PGDomainInterfacesEntitiesLockInterface $lock, DateTime $lockedDateTime)
    {
        $table = $this->getTable();

        $lockedAt = new DateTime();
        $timestamp = $lockedAt->getTimestamp();
        $lockedTimestamp = $lockedDateTime->getTimestamp();

        $pid = $lock->getPid();

        $query = "UPDATE $table SET `locked_at` = '$timestamp' WHERE `pid` = '$pid' AND `locked_at` < '$lockedTimestamp'";

        $result = $this->getDatabaseHandler()->execute($query);

        if ($result === 1) {
            $lock->setLockedAt($lockedAt);
            return true;
        }
    }
}
