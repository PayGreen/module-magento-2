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

use Magento\Sales\Model\Order;

/**
 * Class PGModuleServicesRepositoriesOrderRepository
 *
 * @package PGModule\Services\Repositories
 *
 * @method Magento\Sales\Model\Order createLocalEntity()
 */
class PGModuleServicesRepositoriesOrderRepository extends PGModuleFoundationsAbstractMagentoRepository implements PGDomainInterfacesRepositoriesOrderRepositoryInterface
{
    const ENTITY = 'Magento\Sales\Model\Order';
    const RESOURCE = 'Magento\Sales\Model\ResourceModel\Order';

    public function findByPrimary($id)
    {
        $entity = $this->createLocalEntity();

        $entity->load($id);

        return ($entity->getId() !== null) ? $this->wrapEntity($entity) : null;
    }

    public function findByReference($ref)
    {
        $entity = $this->createLocalEntity();

        $entity->loadByIncrementId($ref);

        return ($entity->getId() !== null) ? $this->wrapEntity($entity) : null;
    }

    public function wrapEntity($localEntity)
    {
        return new PGModuleEntitiesOrder($localEntity);
    }

    /**
     * @inheritDoc
     */
    public function findRefundedAmount(PGDomainInterfacesEntitiesOrderInterface $order)
    {
        /** @var Order $localOrder */
        $localOrder = $order->getLocalEntity();

        return $localOrder->getTotalRefunded();
    }

    public function updateOrderState(PGDomainInterfacesEntitiesOrderInterface $order, array $localState)
    {
        /** @var Order $localEntity */
        $localEntity = $order->getLocalEntity();

        $localEntity
            ->setState($localState['state'])
            ->setStatus($localState['status'])
        ;

        return $this->updateLocalEntity($localEntity);
    }
}
