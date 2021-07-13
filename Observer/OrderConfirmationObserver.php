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
 * @version   2.1.1
 *
 */

namespace Paygreen\Payment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use PGModuleServicesLogger;
use PGSystemServicesContainer;
use PGModuleServicesBroadcaster;
use PGShopComponentsEventsLocalOrder;
use PGMagentoServicesRepositoriesOrderRepository;
use PGShopInterfacesEntitiesOrder;
use Magento\Sales\Model\Order;

class OrderConfirmationObserver implements ObserverInterface
{
    public function __construct()
    {
        require_once PAYGREEN_BOOTSTRAP_SRC;
    }

    public function execute(Observer $observer)
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        /** @var PGModuleServicesBroadcaster $broadcaster */
        $broadcaster = $this->getService('broadcaster');

        /** @var Order $localOrder */
        $localOrder = $observer->getEvent()->getData('order');

        if ($localOrder instanceof Order) {
            $logger->debug("Order confirmation for order #{$localOrder->getId()}");

            /** @var PGMagentoServicesRepositoriesOrderRepository $orderRepository */
            $orderRepository = $this->getService('repository.order');

            /** @var PGShopInterfacesEntitiesOrder $order */
            $order = $orderRepository->wrapEntity($localOrder);

            $broadcaster->fire(new PGShopComponentsEventsLocalOrder('VALIDATION', $order));
        }
    }

    protected function getService($name)
    {
        return PGSystemServicesContainer::getInstance()->get($name);
    }
}