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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGPayment\Services\Handlers;

use PGI\Module\PGFramework\Services\Handlers\RequirementHandler;
use PGI\Module\PGModule\Interfaces\ModuleFacadeInterface;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use PGI\Module\PGPayment\Services\Managers\ButtonManager;
use PGI\Module\PGShop\Interfaces\Provisioners\CheckoutProvisionerInterface;
use PGI\Module\PGSystem\Foundations\AbstractObject;

/**
 * Class CheckoutHandler
 * @package PGPayment\Services\Handlers
 */
class CheckoutHandler extends AbstractObject
{
    /** @var ButtonManager */
    private $buttonManager;

    /** @var PaygreenFacade */
    private $paygreenFacade;

    /** @var ModuleFacadeInterface */
    private $moduleFacade;

    /** @var RequirementHandler */
    private $requirementHandler;

    /** @var Logger */
    private $logger;

    public function __construct(
        RequirementHandler $requirementHandler,
        Logger $logger
    ) {
        $this->requirementHandler = $requirementHandler;
        $this->logger = $logger;
    }

    /**
     * @param PaygreenFacade $paygreenFacade
     */
    public function setPaygreenFacade(PaygreenFacade $paygreenFacade)
    {
        $this->paygreenFacade = $paygreenFacade;
    }

    /**
     * @param ModuleFacadeInterface $moduleFacade
     */
    public function setModuleFacade(ModuleFacadeInterface $moduleFacade)
    {
        $this->moduleFacade = $moduleFacade;
    }

    /**
     * @param ButtonManager $buttonManager
     */
    public function setButtonManager(ButtonManager $buttonManager)
    {
        $this->buttonManager = $buttonManager;
    }

    public function isCheckoutAvailable(CheckoutProvisionerInterface $checkoutProvisioner)
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

        if (!$this->requirementHandler->isFulfilled('payment_activation', true)) {
            $this->logger->warning("PayGreen payments are deactivated.");
            return false;
        }

        $this->logger->notice("PayGreen checkout is available.");

        return true;
    }

    public function hasValidButons(CheckoutProvisionerInterface $checkoutProvisioner)
    {
        $buttons = $this->buttonManager->getValidButtons($checkoutProvisioner);

        $hasButtons = (count($buttons) > 0);

        return $hasButtons;
    }
}
