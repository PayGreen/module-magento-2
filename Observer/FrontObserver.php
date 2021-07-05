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
 * @version   2.1.0
 *
 */

namespace Paygreen\Payment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use PGModuleServicesLogger;
use PGSystemServicesContainer;
use PGModuleComponentsEventsDisplay;
use PGModuleServicesBroadcaster;

class FrontObserver implements ObserverInterface
{
    public function __construct()
    {
        require_once PAYGREEN_BOOTSTRAP_SRC;
    }

    public function execute(Observer $observer)
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');
        
        $logger->debug("Fire front event controller_front_send_response_before from Magento.");

        /** @var PGModuleServicesBroadcaster $broadcaster */
        $broadcaster = $this->getService('broadcaster');

        $broadcaster->fire(new PGModuleComponentsEventsDisplay('frontoffice', 'frontoffice'));
    }

    protected function getService($name)
    {
        return PGSystemServicesContainer::getInstance()->get($name);
    }
}