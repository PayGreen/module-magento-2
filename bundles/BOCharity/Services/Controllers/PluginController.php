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
 * @version   2.5.2
 *
 */

namespace PGI\Module\BOCharity\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGCharity\Services\Handlers\CharityAccountHandler;
use PGI\Module\PGCharity\Services\Handlers\CharityAuthenticationHandler;
use PGI\Module\PGCharity\Services\Managers\GiftManager;
use PGI\Module\PGModule\Components\Events\ProductActivation as ProductActivationEventComponent;
use PGI\Module\PGModule\Services\Broadcaster;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use Exception;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;

/**
 * Class PluginController
 * @package BOCharity\Services\Controllers
 */
class PluginController extends AbstractBackofficeController
{
    /** @var CharityAuthenticationHandler */
    private $charityAuthenticationHandler;

    /** @var CharityAccountHandler */
    private $charityAccountHandler;

    /** @var GiftManager */
    private $giftManager;

    /** @var Broadcaster */
    private $broadcaster;

    public function setCharityAccountHandler(CharityAccountHandler $charityAccountHandler)
    {
        $this->charityAccountHandler = $charityAccountHandler;
    }

    public function setCharityAuthenticationHandler(CharityAuthenticationHandler $charityAuthenticationHandler)
    {
        $this->charityAuthenticationHandler = $charityAuthenticationHandler;
    }

    public function setGiftManager(GiftManager $giftManager)
    {
        $this->giftManager = $giftManager;
    }

    public function setBroadcaster(Broadcaster $broadcaster)
    {
        $this->broadcaster = $broadcaster;
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayAction()
    {
        /** @var Settings $settings */
        $settings = $this->getSettings();

        /** @var ParametersComponent */
        $parameters = $this->getParameters();

        $infos = array();
        $credentials = array();
        $isConnected = false;

        $isCharityActivated = $settings->get('charity_activation');
        $isCharityKitActivated = $settings->get('charity_kit_activation');

        if ($isCharityKitActivated) {
            if ($this->charityAuthenticationHandler->isConnected()) {
                $isConnected = true;
                $server = $settings->get('charity_api_server');
                $url = $parameters["urls.bo_charitykit.$server"];

                $credentials['client_id'] = $settings->get('charity_client_id');
                $credentials['username'] = $settings->get('charity_client_username');

                $infos['gifts_overview'] = $this->getGiftsOverviewData();
                $infos['link_backoffice'] = "https://".$url;

                $infos['is_test_mode_activated'] = $settings->get('charity_test_mode');
                $infos['is_test_mode_expired'] = $this->charityAccountHandler->isTestModeExpired();
                $infos['is_mandate_signed'] = $this->charityAccountHandler->isMandateSigned();
            }
        }

        return $this->buildTemplateResponse('charity/block-charity')
            ->addData('connected', $isConnected)
            ->addData('charityActivated', $isCharityActivated)
            ->addData('charityKitInfos', $infos)
            ->addData('credentials', $credentials)
            ;
    }

    /**
     * @return RedirectionResponseComponent
     * @throws Exception
     */
    public function charityActivationAction()
    {
        $settings = $this->getSettings();

        $charityActivation = $settings->get('charity_activation');

        $settings->set('charity_activation', !$charityActivation);

        if ($charityActivation) {
            $this->success('actions.charity_activation.toggle.result.success.disabled');
        } else {
            $this->broadcaster->fire(new ProductActivationEventComponent('soft', 'charity'));
            $this->success('actions.charity_activation.toggle.result.success.enabled');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.home.display'));
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayProductsAction()
    {
        /** @var Settings $settings */
        $settings = $this->getSettings();

        $isCharityActivated = $settings->get('charity_kit_activation');

        return $this->buildTemplateResponse('charity/block-charity-products')
            ->addData('charityActivated', $isCharityActivated)
        ;
    }

    /**
     * @return RedirectionResponseComponent
     * @throws Exception
     */
    public function charityProductsActivationAction()
    {
        $settings = $this->getSettings();

        $charityActivation = $settings->get('charity_kit_activation');

        $settings->set('charity_kit_activation', !$charityActivation);

        if ($charityActivation) {
            $this->success('actions.charity_activation.toggle.result.success.disabled');
        } else {
            $this->success('actions.charity_activation.toggle.result.success.enabled');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.products.display'));
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getGiftsOverviewData()
    {
        $data = array();

        $data[] = array(
            'period' => 'day',
            'count' => $this->giftManager->getCountOfTheLastHours(),
            'amount' => $this->giftManager->getAmountOfTheLastHours()
        );

        $data[] = array(
            'period' => 'week',
            'count' => $this->giftManager->getCountOfTheLastSevenDays(),
            'amount' => $this->giftManager->getAmountOfTheLastSevenDays()
        );

        $data[] = array(
            'period' => 'month',
            'count' => $this->giftManager->getCountOfTheLastThirtyDays(),
            'amount' => $this->giftManager->getAmountOfTheLastThirtyDays()
        );

        foreach ($data as $index => $value) {
            $data[$index]['amount'] = $this->formatAmount($value['amount']);
        }

        return $data;
    }

    /**
     * @param $amount
     * @return string
     */
    private function formatAmount($amount)
    {
        return number_format($amount, 2, '.', ' ');
    }
}
