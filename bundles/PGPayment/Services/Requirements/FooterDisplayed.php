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
 * @version   2.1.0
 *
 */

/**
 * Class PGPaymentServicesRequirementsFooterDisplayed
 * @package PGPayment\Services\Requirements
 */
class PGPaymentServicesRequirementsFooterDisplayed implements PGFrameworkInterfacesRequirementInterface
{
    /** @var PGModuleServicesSettings */
    private $settings;

    /** @var PGModuleInterfacesModuleFacade */
    private $moduleFacade;

    /** @var PGPaymentServicesPaygreenFacade */
    private $paygreenFacade;

    public function __construct(
        PGModuleServicesSettings $settings,
        PGModuleInterfacesModuleFacade $moduleFacade,
        PGPaymentServicesPaygreenFacade $paygreenFacade
    ) {
        $this->settings = $settings;
        $this->moduleFacade = $moduleFacade;
        $this->paygreenFacade = $paygreenFacade;
    }

    /**
     * @inheritDoc
     */
    public function isFulfilled($arguments = null)
    {
        $isRequired = ($arguments === null) ? true : (bool) $arguments;

        return ($isRequired === $this->isFooterDisplayed());
    }

    protected function isFooterDisplayed()
    {
        $isFooterDisplayed = (bool) $this->settings->get('footer_display');
        $isModuleActivated = $this->moduleFacade->isActive();
        $isPaymentConnected = $this->paygreenFacade->isConnected();

        return ($isFooterDisplayed && $isModuleActivated && $isPaymentConnected);
    }
}
