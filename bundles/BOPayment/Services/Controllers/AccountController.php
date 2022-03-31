<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.1
 *
 */

namespace PGI\Module\BOPayment\Services\Controllers;

use PGI\Module\APIPayment\Components\Response as ResponseComponent;
use PGI\Module\APIPayment\Services\Facades\ApiFacade;
use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGFramework\Services\Handlers\CacheHandler;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGPayment\Services\Facades\PaygreenFacade;
use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use Exception;

/**
 * Class AccountController
 * @package BOPayment\Services\Controllers
 */
class AccountController extends AbstractBackofficeController
{
    /** @var PaygreenFacade */
    private $paygreenFacade;

    /** @var CacheHandler */
    private $cacheHandler;

    public function __construct(
        PaygreenFacade $paygreenFacade,
        CacheHandler $cacheHandler
    ) {
        $this->paygreenFacade = $paygreenFacade;
        $this->cacheHandler = $cacheHandler;
    }

    /**
     * @return RedirectionResponseComponent
     * @throws ResponseException
     * @throws Exception
     */
    public function activateAccountAction()
    {
        /** @var ApiFacade $apiFacade */
        $apiFacade = $this->paygreenFacade->getApiFacade();

        $activate = (bool) $this->getRequest()->get('activation');

        /** @var ResponseComponent $apiResponse */
        $apiResponse = $apiFacade->activateShop($activate);

        if ($apiResponse->isSuccess()) {
            $this->cacheHandler->clearCache();

            $this->success('actions.account_activation.toggle.result.success');
        } else {
            $this->failure('actions.account_activation.toggle.result.failure');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.account.display'));
    }

    /**
     * @return RedirectionResponseComponent
     * @throws Exception
     */
    public function disconnectAction()
    {
        /** @var Settings $settings */
        $settings = $this->getSettings();

        $settings->remove('private_key');
        $settings->remove('public_key');

        $this->success('actions.authentication.reset.result.success');

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.account.display'));
    }

    public function displayAccountInfosAction()
    {
        $infoAccount = '';

        $infos = null;

        if ($this->paygreenFacade->isConnected()) {
            $infoAccount = $this->paygreenFacade->getAccountInfos();
            $infos = array(
                'blocks.account_infos.form.url' => $infoAccount->url,
                'blocks.account_infos.form.siret' => $infoAccount->siret,
                'blocks.account_infos.form.iban' => $infoAccount->IBAN
            );
        }

        return $this->buildTemplateResponse('account/block-infos')
            ->addData('infos', $infos)
        ;
    }
}
