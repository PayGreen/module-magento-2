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
 * @version   2.0.0
 *
 */

/**
 * Class BOPaymentActionsSaveAccountConfiguration
 * @package BOPayment\Actions
 */
class BOPaymentActionsSaveAccountConfiguration extends BOModuleActionsStandardizedSaveSettings
{
    /** @var PGPaymentServicesPaygreenFacade */
    private $paygreenFacade;

    /** @var PGFrameworkServicesHandlersCacheHandler $cacheHandler */
    private $cacheHandler;

    public function setPaygreenFacade(PGPaymentServicesPaygreenFacade $paygreenFacade)
    {
        $this->paygreenFacade = $paygreenFacade;
    }

    public function setCacheHandler(PGFrameworkServicesHandlersCacheHandler $cacheHandler)
    {
        $this->cacheHandler = $cacheHandler;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function process()
    {
        $response = parent::process();

        if ($this->isSuccess()) {
            $this->paygreenFacade->resetApiFacade();
            $this->cacheHandler->clearCache();
        }

        return $response;
    }
}
