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
 * @version   2.0.2
 *
 */

namespace Paygreen\Payment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use PGFrameworkInterfacesHandlersSessionHandlerInterface;
use PGMagentoTreeServicesHandlersLocalCarbonOffset;
use PGModuleServicesLogger;
use PGSystemServicesContainer;

class OrderConfirmationObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        /** @var PGMagentoTreeServicesHandlersLocalCarbonOffset $localCarbonOffsetHandler */
        $localCarbonOffsetHandler = $this->getService('handler.local_carbon_offset');

        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        $localOrder = $observer->getEvent()->getData('order');

        if ($localOrder) {
            $logger->debug("Compute carbon offsetting for order #{$localOrder->getIncrementId()}");
            $localCarbonOffsetHandler->computeCarbonOffset($localOrder);
        }
    }

    protected function getService($name)
    {
        return PGSystemServicesContainer::getInstance()->get($name);
    }
}