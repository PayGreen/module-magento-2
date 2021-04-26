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
 * Class PGPaymentServicesManagersTransactionManager
 *
 * @package PGPayment\Services\Managers
 * @method PGPaymentInterfacesRepositoriesTransactionRepositoryInterface getRepository()
 */
class PGPaymentServicesManagersTransactionManager extends PGDatabaseFoundationsManager
{
    /**
     * @param $id
     * @return PGPaymentInterfacesEntitiesTransactionInterface
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    /**
     * @param string $pid
     * @return PGPaymentInterfacesEntitiesTransactionInterface|null
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
     * @param PGShopInterfacesEntitiesOrder $order
     * @param string $state
     * @param string $mode
     * @param int $amount
     * @return PGPaymentInterfacesEntitiesTransactionInterface
     * @throws Exception
     */
    public function create($pid, PGShopInterfacesEntitiesOrder $order, $state, $mode, $amount)
    {
        /** @var PGPaymentInterfacesEntitiesTransactionInterface $transaction */
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

    public function save(PGPaymentInterfacesEntitiesTransactionInterface $transaction)
    {
        if ($transaction->id() > 0) {
            return $this->getRepository()->update($transaction);
        } else {
            return $this->getRepository()->insert($transaction);
        }
    }

    public function delete(PGPaymentInterfacesEntitiesTransactionInterface $transaction)
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
        /** @var PGPaymentInterfacesEntitiesTransactionInterface $transaction */
        $transaction = $this->getByPid($pid);

        if ($transaction === null) {
            throw new Exception("Transaction with PID '$pid' not found.");
        }

        $transaction->setOrderState($state);

        return $this->save($transaction);
    }
}
