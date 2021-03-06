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
 * @version   1.2.5
 *
 */

/**
 * Class PGDomainServicesRepositoriesTransactionRepository
 * @package PGModule\Services\Repositories
 */
class PGDomainServicesRepositoriesTransactionRepository extends PGFrameworkFoundationsAbstractRepositoryDatabase implements PGDomainInterfacesRepositoriesTransactionRepositoryInterface
{
    /**
     * @inheritdoc
     * @return PGDomainInterfacesEntitiesTransactionInterface|null
     * @throws Exception
     */
    public function findByPid($pid)
    {
        /** @var PGDomainInterfacesEntitiesTransactionInterface $result */
        $result = $this->findOneEntity("`pid` = '$pid'");

        return $result;
    }

    /**
     * @inheritdoc
     * @return PGDomainInterfacesEntitiesTransactionInterface|null
     * @throws Exception
     */
    public function findByOrderPrimary($id_order)
    {
        /** @var PGDomainInterfacesEntitiesTransactionInterface $result */
        $result = $this->findOneEntity("`id_order` = '$id_order'");

        return $result;
    }

    /**
     * @inheritDoc
     * @return PGDomainInterfacesEntitiesTransactionInterface
     * @throws Exception
     */
    public function create()
    {
        /** @var PGDomainInterfacesEntitiesTransactionInterface $result */
        $result = $this->wrapEntity();

        return $result;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function insert(PGDomainInterfacesEntitiesTransactionInterface $transaction)
    {
        return $this->insertEntity($transaction);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function update(PGDomainInterfacesEntitiesTransactionInterface $transaction)
    {
        return $this->updateEntity($transaction);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(PGDomainInterfacesEntitiesTransactionInterface $transaction)
    {
        return $this->deleteEntity($transaction);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function countByOrderPrimary($id_order)
    {
        $id_order = $this->getRequester()->quote($id_order);

        $sql = "SELECT COUNT(*) FROM  `%{database.entities.transaction.table}` WHERE `id_order` = '$id_order';";

        return (int) $this->getRequester()->execute($sql);
    }
}
