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

namespace PGI\Module\BOPayment\Services\Actions;

use PGI\Module\BOModule\Services\Actions\StandardizedSaveSettingsAction;
use PGI\Module\PGFramework\Services\Handlers\CacheHandler;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use Exception;

/**
 * Class SaveAccountConfigurationAction
 * @package BOPayment\Services\Actions
 */
class SaveAccountConfigurationAction extends StandardizedSaveSettingsAction
{
    /** @var PaygreenFacade */
    private $paygreenFacade;

    /** @var CacheHandler $cacheHandler */
    private $cacheHandler;

    public function setPaygreenFacade(PaygreenFacade $paygreenFacade)
    {
        $this->paygreenFacade = $paygreenFacade;
    }

    public function setCacheHandler(CacheHandler $cacheHandler)
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
