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
 * Class PGPaymentServicesHandlersCheckoutHandler
 * @package PGPayment\Services\Handlers
 */
class PGPaymentServicesHandlersCheckoutHandler extends PGSystemFoundationsObject
{
    /** @var PGPaymentServicesManagersButtonManager */
    private $buttonManager;

    /** @var PGPaymentServicesPaygreenFacade */
    private $paygreenFacade;

    /** @var PGModuleInterfacesModuleFacade */
    private $moduleFacade;

    /** @var PGModuleServicesLogger */
    private $logger;

    /** @var PGModuleServicesSettings */
    private $settings;

    public function __construct(PGModuleServicesLogger $logger, PGModuleServicesSettings $settings)
    {
        $this->logger = $logger;
        $this->settings = $settings;
    }

    /**
     * @param PGPaymentServicesPaygreenFacade $paygreenFacade
     */
    public function setPaygreenFacade(PGPaymentServicesPaygreenFacade $paygreenFacade)
    {
        $this->paygreenFacade = $paygreenFacade;
    }

    /**
     * @param PGModuleInterfacesModuleFacade $moduleFacade
     */
    public function setModuleFacade(PGModuleInterfacesModuleFacade $moduleFacade)
    {
        $this->moduleFacade = $moduleFacade;
    }

    /**
     * @param PGPaymentServicesManagersButtonManager $buttonManager
     */
    public function setButtonManager(PGPaymentServicesManagersButtonManager $buttonManager)
    {
        $this->buttonManager = $buttonManager;
    }

    public function isCheckoutAvailable(PGShopInterfacesProvisionersCheckout $checkoutProvisioner)
    {
        if (!$this->moduleFacade->isActive()) {
            $this->logger->warning("PayGreen module is deactivated for checkout.");
            return false;
        }

        if (!$this->paygreenFacade->isConnected()) {
            $this->logger->warning("No PayGreen account available for checkout.");
            return false;
        }

        if (!$this->hasValidButons($checkoutProvisioner)) {
            $this->logger->warning("No available button found for checkout.");
            return false;
        }

        if (!$this->settings->get('active')) {
            $this->logger->warning("PayGreen Payment extension is deactivated.");
            return false;
        }

        $this->logger->notice("PayGreen checkout is available.");

        return true;
    }

    public function hasValidButons(PGShopInterfacesProvisionersCheckout $checkoutProvisioner)
    {
        $buttons = $this->buttonManager->getValidButtons($checkoutProvisioner);

        $hasButtons = (count($buttons) > 0);

        return $hasButtons;
    }
}
