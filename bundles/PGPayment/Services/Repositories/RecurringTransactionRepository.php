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
 * Class PGPaymentServicesRepositoriesRecurringTransactionRepository
 * @package PGPayment\Services\Repositories
 */
class PGPaymentServicesRepositoriesRecurringTransactionRepository extends PGDatabaseFoundationsRepositoryDatabase implements PGPaymentInterfacesRepositoriesRecurringTransactionRepositoryInterface
{
    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findByPid($pid)
    {
        /** @var PGPaymentInterfacesEntitiesRecurringTransactionInterface $result */
        $result = $this->findOneEntity("`pid` = '$pid'");

        return $result;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function insert($pid, $id_order, $state, $stateOrderBefore, $mode, $amount, $rank)
    {
        $dt = new DateTime();

        $entity = $this->wrapEntity(array(
            'pid' => $pid,
            'id_order' => $id_order,
            'state' => $state,
            'state_order_before' => $stateOrderBefore,
            'mode' => $mode,
            'amount' => $amount,
            'rank' => $rank,
            'created_at' => $dt->getTimestamp()
        ));

        $this->insertEntity($entity);

        return $entity;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function updateState(PGPaymentInterfacesEntitiesRecurringTransactionInterface $transaction, $stateOrderAfter)
    {
        $transaction->setStateOrderAfter($stateOrderAfter);

        return $this->updateEntity($transaction);
    }
}
