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
use PGDomainServicesSelectorsPaymentModeSelector;
use PGDomainServicesSelectorsPaymentTypeSelector;
use PGModuleEntitiesButton;
use PGDomainInterfacesEntitiesButtonInterface;
use PGFrameworkServicesHandlersPictureHandler;

class DisplayButtonFields extends AbstractTemplate
{
    /**
     * @return PGDomainInterfacesEntitiesButtonInterface
     */
    public function getButton()
    {
        return $this->getData('button');
    }

    public function hasButton()
    {
        return $this->hasData('button');
    }

    public function getButtonData()
    {
        /** @var PGDomainServicesManagersButtonManager $buttonManager */
        $buttonManager = $this->getService('manager.button');

        /** @var PGModuleEntitiesButton $button */
        $button = $this->hasButton() ? $this->getButton() : $buttonManager->getNew();

        return $button->toArray();
    }

    public function getPictureUrl()
    {
        /** @var PGFrameworkServicesHandlersPictureHandler $pictureHandler */
        $pictureHandler = $this->getService('handler.picture');

        $picturePath = $this->hasButton() ? $this->getButton()->getImageSrc() : null;

        return $pictureHandler->getUrl($picturePath);
    }

    public function id()
    {
        return $this->hasButton() ? $this->getButton()->id() : 0;
    }

    public function isValidInsite()
    {
        return $this->getService('paygreen.facade')->isValidInsite();
    }

    public function getDisplayTypeChoices()
    {
        return array(
            'DEFAULT' => "Texte + Image",
            'TEXT' => "Texte uniquement",
            'IMAGE' => "Image uniquement"
        );
    }

    public function getIntegrationChoices()
    {
        return array(
            'EXTERNAL' => "Paiement sur page externe",
            'INSITE' => "Intégration 'Insite'"
        );
    }

    /**
     * @return array
     */
    public function getPaymentModeChoices()
    {
        /** @var PGDomainServicesSelectorsPaymentModeSelector $selector */
        $selector = $this->getService('selector.payment_mode');

        return $selector->getChoices();
    }

    /**
     * @return array
     */
    public function getPaymentTypeChoices()
    {
        /** @var PGDomainServicesSelectorsPaymentTypeSelector $selector */
        $selector = $this->getService('selector.payment_type');

        return $selector->getChoices();
    }

    public function getPaymentReportChoices()
    {
        return array(
            '0' => "Pas de report",
            '1 week' => "1 semaine",
            '2 weeks' => "2 semaines",
            '1 month' => "1 mois",
            '2 months' => "2 mois",
            '3 months' => "3 mois"
        );
    }
}
