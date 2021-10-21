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
 * @version   2.4.0
 *
 */

namespace Paygreen\Payment\Observer;

use Magento\Framework\Event\Observer as LocalObserver;
use Magento\Framework\Event\ObserverInterface as LocalObserverInterface;
use Magento\Sales\Model\Order as LocalOrder;
use PGI\Module\PGMagento\Services\Repositories\OrderRepository;
use PGI\Module\PGModule\Services\Broadcaster;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGShop\Components\Events\LocalOrder as LocalOrderEventComponent;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGSystem\Services\Container;

class OrderConfirmationObserver implements LocalObserverInterface
{
    public function __construct()
    {
        require_once PAYGREEN_BOOTSTRAP_SRC;
    }

    public function execute(LocalObserver $observer)
    {
        /** @var Logger $logger */
        $logger = $this->getService('logger');

        /** @var Broadcaster $broadcaster */
        $broadcaster = $this->getService('broadcaster');

        /** @var LocalOrder $localOrder */
        $localOrder = $observer->getEvent()->getData('order');

        if ($localOrder instanceof LocalOrder) {
            $logger->debug("Order confirmation for order #{$localOrder->getId()}");

            /** @var OrderRepository $orderRepository */
            $orderRepository = $this->getService('repository.order');

            /** @var OrderEntityInterface $order */
            $order = $orderRepository->wrapEntity($localOrder);

            $broadcaster->fire(new LocalOrderEventComponent('VALIDATION', $order));
        }
    }

    protected function getService($name)
    {
        return Container::getInstance()->get($name);
    }
}