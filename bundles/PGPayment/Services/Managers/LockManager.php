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
 * Class PaygreenTransactionLockManager
 *
 * @package PGPayment\Services\Managers
 * @method PGPaymentInterfacesRepositoriesLockRepositoryInterface getRepository
 */
class PGPaymentServicesManagersLockManager extends PGDatabaseFoundationsManager
{
    const LOCK_DURATION = 3;
    const LOCK_REPEAT = 3;
    const LOCK_WAITING = 2;

    /**
     * @param $pid
     * @return PGPaymentInterfacesEntitiesLockInterface|null
     */
    public function getByPid($pid)
    {
        return $this->getRepository()->findByPid($pid);
    }

    /**
     * @param $pid
     * @return PGPaymentInterfacesEntitiesLockInterface
     * @throws Exception
     */
    public function create($pid)
    {
        return $this->getRepository()->create($pid, new DateTime());
    }

    /**
     * @param string $pid
     * @param int $repeat
     * @return bool
     * @throws Exception
     */
    public function isLocked($pid, $repeat = 0)
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        if ($repeat > self::LOCK_REPEAT) {
            return true;
        }

        $lock = $this->getByPid($pid);

        if ($lock === null) {
            try {
                $logger->debug("Lock creating for PID : $pid");

                $this->create($pid);

                $logger->info("Lock created for PID : $pid");

                return false;
            } catch (Exception $exception) {
                $logger->warning("Lock creating error for PID : $pid");

                return $this->waitForUnlocking($pid, $repeat);
            }
        } elseif (($lock->getLockedAt() > $this->getLockTime())) {
            $logger->warning("Lock actif for PID : $pid");

            return $this->waitForUnlocking($pid, $repeat);
        } else {
            $logger->debug("Lock updating for PID : $pid");

            if ($this->getRepository()->updateLock($lock, $this->getLockTime())) {
                $logger->info("Lock updated for PID : $pid");

                return false;
            } else {
                $logger->warning("Lock updating error for PID : $pid");

                return $this->waitForUnlocking($pid, $repeat);
            }
        }
    }

    /**
     * Returns the current time minus the locking time
     * @return DateTime
     * @throws Exception
     */
    protected function getLockTime()
    {
        return new DateTime('-' . self::LOCK_DURATION . ' seconds');
    }

    /**
     * @param string $pid
     * @param int $repeat
     * @return bool
     * @throws Exception
     */
    protected function waitForUnlocking($pid, $repeat)
    {
        sleep(self::LOCK_WAITING);

        return $this->isLocked($pid, ++ $repeat);
    }
}
