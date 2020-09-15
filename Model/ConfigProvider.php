<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.1.0
 */

namespace Paygreen\Payment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use PGFrameworkContainer;
use PGServerServicesLinker;
use PGFrameworkServicesHandlersPictureHandler;
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

        /** @var PGFrameworkServicesHandlersPictureHandler $pictureHandler */
        $pictureHandler = $this->getService('handler.picture');

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
                    'imageUrl' => $pictureHandler->getUrl($button->getImageSrc()),
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
