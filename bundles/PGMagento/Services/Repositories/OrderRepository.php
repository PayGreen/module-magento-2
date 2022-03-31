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

namespace PGI\Module\PGMagento\Services\Repositories;

use Magento\Sales\Model\Order as LocalOrder;
use PGI\Module\PGMagento\Entities\Order;
use PGI\Module\PGMagento\Foundations\AbstractMagentoRepository;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Interfaces\Repositories\OrderRepositoryInterface;

/**
 * Class OrderRepository
 *
 * @package PGMagento\Services\Repositories
 *
 * @method LocalOrder createLocalEntity()
 */
class OrderRepository extends AbstractMagentoRepository implements OrderRepositoryInterface
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
        return new Order($localEntity);
    }

    /**
     * @inheritDoc
     */
    public function findRefundedAmount(OrderEntityInterface $order)
    {
        /** @var LocalOrder $localOrder */
        $localOrder = $order->getLocalEntity();

        return $localOrder->getTotalRefunded();
    }

    public function updateOrderState(OrderEntityInterface $order, array $localState)
    {
        /** @var LocalOrder $localEntity */
        $localEntity = $order->getLocalEntity();

        $localEntity
            ->setState($localState['state'])
            ->setStatus($localState['status'])
        ;

        return $this->updateLocalEntity($localEntity);
    }
}
