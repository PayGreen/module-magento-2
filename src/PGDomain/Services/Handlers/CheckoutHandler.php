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
 * @version   1.0.1
 */

/**
 * Class PGDomainServicesHandlersCheckoutHandler
 * @package PGFramework\Services\Handlers
 */
class PGDomainServicesHandlersCheckoutHandler extends PGFrameworkFoundationsAbstractObject
{
    /** @var PGDomainServicesManagersButtonManager */
    private $buttonManager;

    /** @var PGDomainServicesPaygreenFacade */
    private $paygreenFacade;

    /** @var PGFrameworkInterfacesModuleFacadeInterface */
    private $moduleFacade;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    public function __construct(PGFrameworkServicesLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param PGDomainServicesPaygreenFacade $paygreenFacade
     */
    public function setPaygreenFacade(PGDomainServicesPaygreenFacade $paygreenFacade)
    {
        $this->paygreenFacade = $paygreenFacade;
    }

    /**
     * @param PGFrameworkInterfacesModuleFacadeInterface $moduleFacade
     */
    public function setModuleFacade(PGFrameworkInterfacesModuleFacadeInterface $moduleFacade)
    {
        $this->moduleFacade = $moduleFacade;
    }

    /**
     * @param PGDomainServicesManagersButtonManager $buttonManager
     */
    public function setButtonManager(PGDomainServicesManagersButtonManager $buttonManager)
    {
        $this->buttonManager = $buttonManager;
    }

    public function isCheckoutAvailable(PGDomainInterfacesCheckoutProvisionerInterface $checkoutProvisioner)
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

        return true;
    }

    public function hasValidButons(PGDomainInterfacesCheckoutProvisionerInterface $checkoutProvisioner)
    {
        $buttons = $this->buttonManager->getValidButtons($checkoutProvisioner);

        $hasButtons = (count($buttons) > 0);

        return $hasButtons;
    }
}
