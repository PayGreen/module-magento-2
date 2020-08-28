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
 * @version   1.0.1
 */

/**
 * Class PGDomainServicesRepositoriesRecurringTransactionRepository
 * @package PGModule\Services\Repositories
 */
class PGDomainServicesRepositoriesRecurringTransactionRepository extends PGFrameworkFoundationsAbstractRepositoryDatabase implements PGDomainInterfacesRepositoriesRecurringTransactionRepositoryInterface
{
    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findByPid($pid)
    {
        /** @var PGDomainInterfacesEntitiesRecurringTransactionInterface $result */
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
    public function updateState(PGDomainInterfacesEntitiesRecurringTransactionInterface $transaction, $stateOrderAfter)
    {
        $transaction->setStateOrderAfter($stateOrderAfter);

        return $this->updateEntity($transaction);
    }
}
