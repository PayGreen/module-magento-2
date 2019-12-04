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
 * @version   0.3.3
 */

use Paygreen\Payment\Model\Transaction;

class PGModuleServicesRepositoriesTransactionRepository extends PGModuleFoundationsAbstractMagentoRepository implements PGDomainInterfacesRepositoriesTransactionRepositoryInterface
{
    const ENTITY = 'Paygreen\Payment\Model\Transaction';
    const RESOURCE = 'Paygreen\Payment\Model\ResourceModel\Transaction';

    /**
     * @inheritdoc
     */
    public function findByPid($pid)
    {
        return $this->findByField('pid', $pid);
    }

    public function findByOrderPrimary($id_order)
    {
        return $this->findByField('id_order', $id_order);
    }

    public function create()
    {
        /** @var Transaction $lock */
        $localEntity = $this->createLocalEntity();

        return $this->wrapEntity($localEntity);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function insert(PGDomainInterfacesEntitiesTransactionInterface $transaction)
    {
        return $this->insertLocalEntity($transaction->getLocalEntity());
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function update(PGDomainInterfacesEntitiesTransactionInterface $transaction)
    {
        return $this->updateLocalEntity($transaction->getLocalEntity());
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(PGDomainInterfacesEntitiesTransactionInterface $transaction)
    {
        return $this->deleteLocalEntity($transaction->getLocalEntity());
    }

    /**
     * @inheritDoc
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function countByOrderPrimary($id_order)
    {
        $sql = "SELECT COUNT(*) FROM `{$this->getTable()}` WHERE `id_order` = $id_order";

        return $this->getDatabaseHandler()->fetchOne($sql);
    }

    public function wrapEntity($localEntity)
    {
        return new PGModuleEntitiesTransaction($localEntity);
    }
}
