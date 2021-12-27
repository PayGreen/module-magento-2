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

namespace PGI\Module\PGPayment\Services\Requirements;

use Exception;
use PGI\Module\PGFramework\Interfaces\RequirementInterface;
use PGI\Module\PGModule\Interfaces\ModuleFacadeInterface;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;

/**
 * Class FooterDisplayedRequirement
 * @package PGPayment\Services\Requirements
 */
class FooterDisplayedRequirement implements RequirementInterface
{
    /** @var Settings */
    private $settings;

    /** @var ModuleFacadeInterface */
    private $moduleFacade;

    /** @var PaygreenFacade */
    private $paygreenFacade;

    public function __construct(
        Settings $settings,
        ModuleFacadeInterface $moduleFacade,
        PaygreenFacade $paygreenFacade
    ) {
        $this->settings = $settings;
        $this->moduleFacade = $moduleFacade;
        $this->paygreenFacade = $paygreenFacade;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function isValid()
    {
        return $this->isFooterDisplayed();
    }

    /**
     * @throws Exception
     */
    protected function isFooterDisplayed()
    {
        $isFooterDisplayed = (bool) $this->settings->get('footer_display');
        $isModuleActivated = $this->moduleFacade->isActive();
        $isPaymentConnected = $this->paygreenFacade->isConnected();

        return ($isFooterDisplayed && $isModuleActivated && $isPaymentConnected);
    }
}
