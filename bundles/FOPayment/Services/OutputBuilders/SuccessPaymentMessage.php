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

/**
 * Class FOPaymentServicesOutputBuildersSuccessPaymentMessage
 * @package FOPayment\Services\OutputBuilders
 */
class FOPaymentServicesOutputBuildersSuccessPaymentMessage extends PGModuleFoundationsOutputBuilder
{
    /** @var PGIntlServicesHandlersTranslationHandler */
    private $translationHandler;

    /** @var PGViewServicesHandlersViewHandler */
    private $viewHandler;

    /** @var PGServerServicesHandlersLink */
    private $linkHandler;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(
        PGIntlServicesHandlersTranslationHandler $translationHandler,
        PGViewServicesHandlersViewHandler $viewHandler,
        PGServerServicesHandlersLink $linkHandler,
        PGModuleServicesLogger $logger
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
        } elseif (!$data['order'] instanceof PGShopInterfacesEntitiesOrder) {
            throw new Exception("Order must be an instance of PGShopInterfacesEntitiesOrder.");
        }

        /** @var PGShopInterfacesEntitiesOrder $order */
        $order = $data['order'];

        /** @var PGModuleComponentsOutput $output */
        $output = new PGModuleComponentsOutput();

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