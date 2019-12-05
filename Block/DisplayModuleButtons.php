<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.4
 */

namespace Paygreen\Payment\Block;

use Paygreen\Payment\Foundations\AbstractTemplate;
use PGDomainServicesManagersButtonManager;
use PGDomainInterfacesEntitiesButtonInterface;
use PGDomainServicesSelectorsPaymentModeSelector;
use PGDomainServicesSelectorsPaymentTypeSelector;
use PGFrameworkServicesHandlersPictureHandler;

class DisplayModuleButtons extends AbstractTemplate
{
    public function getButtons()
    {
        /** @var PGDomainServicesManagersButtonManager $buttonManager */
        $buttonManager = $this->getService('manager.button');

        return $buttonManager->getAll();
    }

    public function isValidShop()
    {
        return ($this->getService('paygreen.facade')->getStatusShop() !== null);
    }

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return string
     */
    public function getPaymentModeName(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        /** @var PGDomainServicesSelectorsPaymentModeSelector $selector */
        $selector = $this->getService('selector.payment_mode');

        return $selector->getName($button->getPaymentMode());
    }

    /**
     * @param PGDomainInterfacesEntitiesButtonInterface $button
     * @return string
     */
    public function getPaymentTypeName(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        /** @var PGDomainServicesSelectorsPaymentTypeSelector $selector */
        $selector = $this->getService('selector.payment_type');

        return $selector->getName($button->getPaymentType());
    }

    public function getPictureUrl(PGDomainInterfacesEntitiesButtonInterface $button)
    {
        /** @var PGFrameworkServicesHandlersPictureHandler $pictureHandler */
        $pictureHandler = $this->getService('handler.picture');

        return $pictureHandler->getUrl($button->getImageSrc());
    }
}
