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

namespace Paygreen\Payment\Block;

use Exception;
use Magento\Checkout\Model\Session as LocalSession;
use Magento\Framework\View\Element\Template as LocalTemplate;
use Magento\Framework\View\Element\Template\Context as LocalContext;
use Magento\Sales\Model\Order as LocalOrder;
use PGI\Module\PGMagento\Services\Repositories\OrderRepository;
use PGI\Module\PGModule\Components\Output as OutputComponent;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGModule\Services\Providers\OutputProvider;
use PGI\Module\PGSystem\Services\Container;

class DisplaySuccessOutput extends LocalTemplate
{
    /** @var LocalSession  */
    protected $checkoutSession;

    public function __construct(LocalSession $checkoutSession, LocalContext $context)
    {
        parent::__construct($context);

        $this->checkoutSession = $checkoutSession;

        require_once PAYGREEN_BOOTSTRAP_SRC;
    }

    protected function getService($name)
    {
        return Container::getInstance()->get($name);
    }

    protected function _toHtml()
    {
        /** @var LoggerInterface $logger */
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
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger.view');

        /** @var OutputProvider */
        $outputProvider = $this->getService('provider.output');

        /** @var LocalOrder $localOrder */
        $localOrder = $this->checkoutSession->getLastRealOrder();

        $content = '';

        if ($localOrder instanceof LocalOrder) {
            /** @var OrderRepository $orderRepository */
            $orderRepository = $this->getService('repository.order');

            /** @var OutputComponent $output */
            $output = $outputProvider->getZoneOutput('FRONT.FUNNEL.CONFIRMATION', array(
                'order' => $orderRepository->wrapEntity($localOrder)
            ));

            $content = $output->getContent();
        } else {
            $logger->warning("LocalOrder is not correctly defined in Build FRONT.FUNNEL.CONFIRMATION");
        }

        return $content;
    }

}
