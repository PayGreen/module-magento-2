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
 * @version   0.3.5
 */

/**
 * Class PGDomainServicesHandlersCheckoutHandler
 * @package PGFramework\Services\Handlers
 */
class PGDomainServicesHandlersSelectHandler extends PGFrameworkFoundationsAbstractObject
{
    /** @var PGDomainServicesManagersPaymentTypeManager */
    private $paymentTypeManager;

    /** @var PGDomainServicesPaygreenFacade */
    private $paygreenFacade;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    /** @var PGFrameworkServicesHandlersTranslatorHandler */
    private $translatorHandler;

    public function __construct(PGFrameworkServicesLogger $logger)
    {
        $this->logger = $logger;

        $this->logger->warning("Use deprecated service 'handler.selector'. Use dedicated selector services instead.");
    }

    /**
     * @param PGDomainServicesPaygreenFacade $paygreenFacade
     */
    public function setPaygreenFacade(PGDomainServicesPaygreenFacade $paygreenFacade)
    {
        $this->paygreenFacade = $paygreenFacade;
    }

    /**
     * @param PGDomainServicesManagersPaymentTypeManager $paymentTypeManager
     */
    public function setPaymentTypeManager($paymentTypeManager)
    {
        $this->paymentTypeManager = $paymentTypeManager;
    }

    /**
     * @param PGFrameworkServicesHandlersTranslatorHandler $translatorHandler
     */
    public function setTranslatorHandler($translatorHandler)
    {
        $this->translatorHandler = $translatorHandler;
    }

    /**
     * @return array
     */
    public function getPaymentModeChoices()
    {
        $choices = array();

        $codes = $this->paygreenFacade->getAvailablePaymentModes();

        foreach ($codes as $code) {
            if ($this->translatorHandler->has("data.payment_modes.$code")) {
                $choices[$code] = $this->translatorHandler->get("data.payment_modes.$code");
            } else {
                $this->logger->warning("Unavailable payment mode label for '$code'.");
                $choices[$code] = $code;
            }
        }

        return $choices;
    }

    /**
     * @return array
     * @throws PGClientExceptionsPaymentRequestException
     */
    public function getPaymentTypeChoices()
    {
        $choices = array();

        $codes = $this->paymentTypeManager->getCodes();

        foreach ($codes as $code) {
            if ($this->translatorHandler->has("data.payment_types.$code")) {
                $choices[$code] = $this->translatorHandler->get("data.payment_types.$code");
            } else {
                $this->logger->warning("Unavailable payment type label for '$code'.");

                $choices[$code] = $code;
            }
        }

        return $choices;
    }
}
