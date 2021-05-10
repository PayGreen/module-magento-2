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
 * @version   2.0.2
 *
 */

/**
 * Class PGPaymentServicesListenersInstallDefaultButtonListener
 * @package PGPayment\Services\Listeners
 */
class PGPaymentServicesListenersInstallDefaultButtonListener
{
    /** @var PGPaymentServicesManagersButtonManager */
    private $buttonManager;

    /** @var PGIntlServicesManagersTranslationManager */
    private $translationManager;

    /** @var PGModuleServicesLogger */
    private $logger;

    private $bin;

    public function __construct(
        PGPaymentServicesManagersButtonManager $buttonManager,
        PGIntlServicesManagersTranslationManager $translationManager,
        PGModuleServicesLogger $logger
    ) {
        $this->buttonManager = $buttonManager;
        $this->translationManager = $translationManager;
        $this->logger = $logger;
    }

    public function listen(PGModuleComponentsEventsModule $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;

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
