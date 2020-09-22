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
 * @version   1.1.1
 */

class PGDomainServicesListenersInstallDefaultButtonListener
{
    /** @var PGDomainServicesManagersButtonManager */
    private $buttonManager;

    /** @var PGIntlServicesManagersTranslationManager */
    private $translationManager;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    public function __construct(
        PGDomainServicesManagersButtonManager $buttonManager,
        PGIntlServicesManagersTranslationManager $translationManager,
        PGFrameworkServicesLogger $logger
    ) {
        $this->buttonManager = $buttonManager;
        $this->translationManager = $translationManager;
        $this->logger = $logger;
    }

    public function listen(PGFrameworkComponentsEventsModuleEvent $event)
    {
        if ($this->buttonManager->count() === 0) {
            $button = $this->buttonManager->getNew()
                ->setPaymentType('CB')
                ->setPosition(1)
                ->setImageHeight(60)
                ->setDisplayType('DEFAULT')
                ->setPaymentNumber(1)
                ->setDiscount(0)
            ;

            if (!$this->buttonManager->save($button)) {
                throw new Exception("Unable to create default button.");
            } else {
                $key = 'button-' . $button->id();
                $this->translationManager->saveByCode($key, array(
                    'fr' => "Payer par carte bancaire",
                    'en' => "Pay by credit card"
                ));

                $this->logger->info("Default button successfully created.");
            }
        } else {
            $this->logger->error("Default button already exists.");
        }
    }
}
