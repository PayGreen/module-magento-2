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
 * @version   2.2.0
 *
 */

namespace Paygreen\Payment\Observer;

use Magento\Framework\Event\Observer as LocalObserver;
use Magento\Framework\Event\ObserverInterface as LocalObserverInterface;
use PGI\Module\PGModule\Components\Events\Display as DisplayEventComponent;
use PGI\Module\PGModule\Services\Broadcaster;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGSystem\Services\Container;

class FrontObserver implements LocalObserverInterface
{
    public function __construct()
    {
        require_once PAYGREEN_BOOTSTRAP_SRC;
    }

    public function execute(LocalObserver $observer)
    {
        /** @var Logger $logger */
        $logger = $this->getService('logger');
        
        $logger->debug("Fire front event controller_front_send_response_before from Magento.");

        /** @var Broadcaster $broadcaster */
        $broadcaster = $this->getService('broadcaster');

        $broadcaster->fire(new DisplayEventComponent('frontoffice', 'frontoffice'));
    }

    protected function getService($name)
    {
        return Container::getInstance()->get($name);
    }
}