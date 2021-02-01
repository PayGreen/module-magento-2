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
 * @version   1.2.1
 *
 */

namespace Paygreen\Payment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use PGFrameworkContainer;
use PGServerServicesLinker;
use PGDomainServicesHandlersPaymentButtonHandler;
use PGDomainInterfacesEntitiesButtonInterface;
use PGModuleProvisionersCheckoutProvisioner;

class ConfigProvider implements ConfigProviderInterface
{
    public function __construct()
    {
        require_once PAYGREEN_BOOTSTRAP_SRC;
    }

    protected function getService($name)
    {
        return PGFrameworkContainer::getInstance()->get($name);
    }

    public function getConfig()
    {
        $this->getService('logger')->debug("ConfigProvider called.");

        /** @var PGDomainServicesHandlersPaymentButtonHandler $paymentButtonHandler */
        $paymentButtonHandler = $this->getService('handler.payment_button');

        /** @var \PGIntlServicesTranslatior $translator */
        $translator = $this->getService('translator');

        /** @var PGServerServicesLinker $linker */
        $linker = $this->getService('linker');

        /** @var PGModuleProvisionersCheckoutProvisioner $checkoutProvisioner */
        $checkoutProvisioner = new PGModuleProvisionersCheckoutProvisioner();

        $buttons = $this->getService('manager.button')->getValidButtons($checkoutProvisioner);

        $formatedButtons = [];

        /** @var PGDomainInterfacesEntitiesButtonInterface $button */
        foreach ($buttons as $button) {
            $formatedButtons[] = array_merge(
                $button->toArray(),
                [
                    'imageUrl' => $paymentButtonHandler->getButtonFinalUrl($button),
                    'url' => $linker->buildFrontOfficeUrl('front.payment.validation', ['id' => $button->id()])
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
