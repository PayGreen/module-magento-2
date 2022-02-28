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
 * @version   2.6.0
 *
 */

namespace PGI\Module\FOPayment\Services\OutputBuilders;

use PGI\Module\PGIntl\Services\Handlers\TranslationHandler;
use PGI\Module\PGModule\Components\Output as OutputComponent;
use PGI\Module\PGModule\Foundations\AbstractOutputBuilder;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGServer\Services\Handlers\LinkHandler;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGView\Services\Handlers\ViewHandler;
use Exception;

/**
 * Class SuccessPaymentMessageOutputBuilder
 * @package FOPayment\Services\OutputBuilders
 */
class SuccessPaymentMessageOutputBuilder extends AbstractOutputBuilder
{
    /** @var TranslationHandler */
    private $translationHandler;

    /** @var ViewHandler */
    private $viewHandler;

    /** @var LinkHandler */
    private $linkHandler;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        TranslationHandler $translationHandler,
        ViewHandler $viewHandler,
        LinkHandler $linkHandler,
        LoggerInterface $logger
    ) {
        parent::__construct();

        $this->translationHandler = $translationHandler;
        $this->viewHandler = $viewHandler;
        $this->linkHandler = $linkHandler;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function build(array $data = array())
    {
        if (!array_key_exists('order', $data)) {
            throw new Exception("Order is required to build success page thank you message.");
        } elseif (!$data['order'] instanceof OrderEntityInterface) {
            throw new Exception("Order must be an instance of OrderEntityInterface.");
        }

        /** @var OrderEntityInterface $order */
        $order = $data['order'];

        /** @var OutputComponent $output */
        $output = new OutputComponent();

        if ($order->paidWithPaygreen()) {
            $orderId = $order->id();

            $this->logger->debug("Display thank you message for order '$orderId'");

            $url = $this->linkHandler->buildLocalUrl('order', array('id_order' => $orderId));

            $thankYouMessage = $this->viewHandler->renderTemplate('block-payment-confirmation', array(
                'message' => $this->translationHandler->translate('message_payment_success'),
                'url' => array(
                    'link' => $url,
                    'text' => 'frontoffice.payment.results.order.validate.link'
                )
            ));

            $output->addContent($thankYouMessage);
        }

        return $output;
    }
}
