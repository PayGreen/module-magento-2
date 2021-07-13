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

namespace Paygreen\Payment\Block;

use Exception;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use PGSystemServicesContainer;
use PGModuleServicesLogger;
use PGModuleServicesProvidersOutput;
use PGMagentoServicesRepositoriesOrderRepository;
use Magento\Sales\Model\Order;
use PGModuleComponentsOutput;

class DisplaySuccessOutput extends Template
{
    /** @var CheckoutSession  */
    protected $checkoutSession;

    public function __construct(CheckoutSession $checkoutSession, Context $context)
    {
        parent::__construct($context);

        $this->checkoutSession = $checkoutSession;

        require_once PAYGREEN_BOOTSTRAP_SRC;
    }

    protected function getService($name)
    {
        return PGSystemServicesContainer::getInstance()->get($name);
    }

    protected function _toHtml()
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger.view');

        $content = parent::_toHtml();

        $size = strlen($content);

        $logger->debug("Displaying output content with size of {$size} octets.");

        return $content;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getContent()
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger.view');

        /** @var PGModuleServicesProvidersOutput */
        $outputProvider = $this->getService('provider.output');

        /** @var Order $localOrder */
        $localOrder = $this->checkoutSession->getLastRealOrder();

        $content = '';

        if ($localOrder instanceof Order) {
            $logger->debug("Build FUNNEL.CONFIRMATION channel for order #{$localOrder->getId()}");

            /** @var PGMagentoServicesRepositoriesOrderRepository $orderRepository */
            $orderRepository = $this->getService('repository.order');

            /** @var PGModuleComponentsOutput $output */
            $output = $outputProvider->getZoneOutput('FUNNEL.CONFIRMATION', array(
                'order' => $orderRepository->wrapEntity($localOrder)
            ));

            $content = $output->getContent();
        }

        return $content;
    }

}
