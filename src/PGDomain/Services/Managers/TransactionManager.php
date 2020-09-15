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
 * @version   1.1.0
 */

/**
 * Class PGModuleServicesManagersTransactionManager
 *
 * @package PGDomain\Services\Managers
 * @method PGDomainInterfacesRepositoriesTransactionRepositoryInterface getRepository()
 */
class PGDomainServicesManagersTransactionManager extends PGFrameworkFoundationsAbstractManager
{
    /**
     * @param $id
     * @return PGDomainInterfacesEntitiesTransactionInterface
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    /**
     * @param string $pid
     * @return PGDomainInterfacesEntitiesTransactionInterface|null
     */
    public function getByPid($pid)
    {
        return $this->getRepository()->findByPid($pid);
    }

    public function getByOrderPrimary($id_order)
    {
        return $this->getRepository()->findByOrderPrimary($id_order);
    }

    /**
     * @param string $pid
     * @param PGDomainInterfacesEntitiesOrderInterface $order
     * @param string $state
     * @param string $mode
     * @param int $amount
     * @return PGDomainInterfacesEntitiesTransactionInterface
     * @throws Exception
     */
    public function create($pid, PGDomainInterfacesEntitiesOrderInterface $order, $state, $mode, $amount)
    {
        /** @var PGDomainInterfacesEntitiesTransactionInterface $transaction */
        $transaction = $this->getRepository()->create();

        $transaction
            ->setPid($pid)
            ->setOrder($order)
            ->setOrderState($state)
            ->setMode($mode)
            ->setAmount($amount)
            ->setCreatedAt(new DateTime())
        ;

        return $transaction;
    }

    public function save(PGDomainInterfacesEntitiesTransactionInterface $transaction)
    {
        if ($transaction->id() > 0) {
            return $this->getRepository()->update($transaction);
        } else {
            return $this->getRepository()->insert($transaction);
        }
    }

    public function delete(PGDomainInterfacesEntitiesTransactionInterface $transaction)
    {
        return $this->getRepository()->delete($transaction);
    }

    /**
     * Check if an order was payed with PayGreen
     * @param int $id_order
     * @return bool
     */
    public function hasTransaction($id_order)
    {
        $count = $this->getRepository()->countByOrderPrimary($id_order);

        return ($count > 0);
    }

    /**
     * @param string $pid
     * @param string $state
     * @return bool
     * @throws Exception
     */
    public function updateTransaction($pid, $state)
    {
        /** @var PGDomainInterfacesEntitiesTransactionInterface $transaction */
        $transaction = $this->getByPid($pid);

        if ($transaction === null) {
            throw new Exception("Transaction with PID '$pid' not found.");
        }

        $transaction->setOrderState($state);

        return $this->save($transaction);
    }
}
