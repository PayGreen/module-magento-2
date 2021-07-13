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

namespace Paygreen\Payment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use PGIntlServicesTranslator;
use PGSystemServicesContainer;
use PGServerServicesHandlersLink;
use PGPaymentServicesHandlersPaymentButtonHandler;
use PGPaymentInterfacesEntitiesButtonInterface;
use PGMagentoProvisionersCheckoutProvisioner;

class ConfigProvider implements ConfigProviderInterface
{
    public function __construct()
    {
        require_once PAYGREEN_BOOTSTRAP_SRC;
    }

    protected function getService($name)
    {
        return PGSystemServicesContainer::getInstance()->get($name);
    }

    public function getConfig()
    {
        $this->getService('logger')->debug("ConfigProvider called.");

        /** @var PGPaymentServicesHandlersPaymentButtonHandler $paymentButtonHandler */
        $paymentButtonHandler = $this->getService('handler.payment_button');

        /** @var PGIntlServicesTranslator $translator */
        $translator = $this->getService('translator');

        /** @var PGServerServicesHandlersLink $linkHandler */
        $linkHandler = $this->getService('handler.link');

        /** @var PGMagentoProvisionersCheckoutProvisioner $checkoutProvisioner */
        $checkoutProvisioner = new PGMagentoProvisionersCheckoutProvisioner();

        $buttons = $this->getService('manager.button')->getValidButtons($checkoutProvisioner);

        $formatedButtons = [];

        /** @var PGPaymentInterfacesEntitiesButtonInterface $button */
        foreach ($buttons as $button) {
            $formatedButtons[] = array_merge(
                $button->toArray(),
                [
                    'imageUrl' => $paymentButtonHandler->getButtonFinalUrl($button),
                    'url' => $linkHandler->buildFrontOfficeUrl('front.payment.validation', ['id' => $button->id()])
                ]
            );
        }

        return [
            'paygreen' => [
                'title' => $translator->get('~payment_bloc'),
                'submitTitle' => $translator->get('frontoffice.payment.link.title'),
                'confirmation' => $translator->get('~payment_link'),
                'buttons' => $formatedButtons
            ]
        ];
    }
}
