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

use Paygreen\Payment\Model\RecurringTransaction;

class PGModuleServicesRepositoriesRecurringTransactionRepository extends PGModuleFoundationsAbstractMagentoRepository implements PGDomainInterfacesRepositoriesRecurringTransactionRepositoryInterface
{
    const ENTITY = 'Paygreen\Payment\Model\RecurringTransaction';
    const RESOURCE = 'Paygreen\Payment\Model\ResourceModel\RecurringTransaction';

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
    public function insert($pid, $id_order, $state, $stateOrderBefore, $mode, $amount, $rank)
    {
        $date = new DateTime();

        /** @var RecurringTransaction $localEntity */
        $localEntity = $this->createLocalEntity([
            'pid' => $pid,
            'id_order' => $id_order,
            'state' => $state,
            'state_order_before' => $stateOrderBefore,
            'mode' => $mode,
            'amount' => (int) $amount,
            'rank' => $rank,
            'created_at' => $date->getTimestamp()
        ]);

        if ($this->insertLocalEntity($localEntity)) {
            return $this->wrapEntity($localEntity);
        } else {
            throw new Exception("Unable to save Transaction entity.");
        }
    }

    /**
     * @inheritdoc
     */
    public function updateState(PGDomainInterfacesEntitiesRecurringTransactionInterface $transaction, $stateOrderAfter)
    {
        /** @var Paygreen\Payment\Model\RecurringTransaction $localEntity */
        $localEntity = $transaction->getLocalEntity();

        $localEntity->setData('state_order_after', $stateOrderAfter);

        return $this->updateLocalEntity($localEntity);
    }

    /**
     * @param PGLocalEntitiesRecurringTransaction $localEntity
     * @return PGDomainInterfacesEntitiesRecurringTransactionInterface
     */
    public function wrapEntity($localEntity)
    {
        return new PGModuleEntitiesRecurringTransaction($localEntity);
    }
}
