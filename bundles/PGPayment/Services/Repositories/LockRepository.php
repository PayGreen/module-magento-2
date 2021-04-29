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
 * @version   2.0.1
 *
 */

/**
 * Class PGPaymentServicesRepositoriesLockRepository
 * @package PGPayment\Services\Repositories
 */
class PGPaymentServicesRepositoriesLockRepository extends PGDatabaseFoundationsRepositoryDatabase implements PGPaymentInterfacesRepositoriesLockRepositoryInterface
{
    private $bin;

    /**
     * @inheritdoc
     * @return PGPaymentInterfacesEntitiesLockInterface
     * @throws Exception
     */
    public function create($pid, DateTime $dateTime)
    {
        // Thrashing unused arguments
        $this->bin = $dateTime;

        $dt = new DateTime();

        /** @var PGPaymentInterfacesEntitiesLockInterface $entity */
        $entity = $this->wrapEntity(array(
            'pid' => $pid,
            'locked_at' => $dt->getTimestamp()
        ));

        $this->insertEntity($entity);

        return $entity;
    }

    /**
     * @inheritdoc
     * @return PGPaymentInterfacesEntitiesLockInterface|null
     * @throws Exception
     */
    public function findByPid($pid)
    {
        /** @var PGPaymentInterfacesEntitiesLockInterface $result */
        $result = $this->findOneEntity("pid='$pid'");

        return $result;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function updateLock(PGPaymentInterfacesEntitiesLockInterface $lock, DateTime $lockedDateTime)
    {
        $lockedAt = new DateTime();
        $timestamp = $lockedAt->getTimestamp();
        $lockedTimestamp = $lockedDateTime->getTimestamp();

        $where = "`pid` = '{$lock->getPid()}' AND `locked_at` < '$lockedTimestamp'";

        $query = "UPDATE `%{database.entities.lock.table}` SET `locked_at` = '$timestamp' WHERE $where";

        if ($this->getRequester()->execute($query)) {
            $lock->setLockedAt($lockedAt);

            return $this->updateEntity($lock);
        }

        return false;
    }
}
