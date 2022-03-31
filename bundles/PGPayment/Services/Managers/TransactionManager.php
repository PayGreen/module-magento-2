<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGPayment\Services\Managers;

use PGI\Module\PGDatabase\Foundations\AbstractManager;
use PGI\Module\PGPayment\Interfaces\Entities\TransactionEntityInterface;
use PGI\Module\PGPayment\Interfaces\Repositories\TransactionRepositoryInterface;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use DateTime;
use Exception;

/**
 * Class TransactionManager
 *
 * @package PGPayment\Services\Managers
 * @method TransactionRepositoryInterface getRepository()
 */
class TransactionManager extends AbstractManager
{
    /**
     * @param $id
     * @return TransactionEntityInterface
     */
    public function getByPrimary($id)
    {
        return $this->getRepository()->findByPrimary($id);
    }

    /**
     * @param string $pid
     * @return TransactionEntityInterface|null
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
     * @param OrderEntityInterface $order
     * @param string $state
     * @param string $mode
     * @param int $amount
     * @return TransactionEntityInterface
     * @throws Exception
     */
    public function create($pid, OrderEntityInterface $order, $state, $mode, $amount)
    {
        /** @var TransactionEntityInterface $transaction */
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

    public function save(TransactionEntityInterface $transaction)
    {
        if ($transaction->id() > 0) {
            return $this->getRepository()->update($transaction);
        } else {
            return $this->getRepository()->insert($transaction);
        }
    }

    public function delete(TransactionEntityInterface $transaction)
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
        /** @var TransactionEntityInterface $transaction */
        $transaction = $this->getByPid($pid);

        if ($transaction === null) {
            throw new Exception("Transaction with PID '$pid' not found.");
        }

        $transaction->setOrderState($state);

        return $this->save($transaction);
    }
}
