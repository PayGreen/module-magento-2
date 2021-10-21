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
 * @version   2.4.0
 *
 */

namespace PGI\Module\BOTree\Services\Controllers;

use PGI\Module\BOModule\Foundations\Controllers\AbstractBackofficeController;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use PGI\Module\PGServer\Components\Responses\Template as TemplateResponseComponent;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;
use PGI\Module\PGTree\Services\Handlers\TreeAccountHandler;
use PGI\Module\PGTree\Services\Handlers\TreeAuthenticationHandler;
use PGI\Module\PGTree\Services\Managers\CarbonDataManager;
use Exception;

/**
 * Class PluginController
 * @package BOTree\Services\Controllers
 */
class PluginController extends AbstractBackofficeController
{
    /** @var TreeAuthenticationHandler */
    private $treeAuthenticationHandler;

    /** @var CarbonDataManager */
    private $carbonDataManager;

    /** @var TreeAccountHandler */
    private $treeAccountHandler;

    public function setTreeAuthenticationHandler(TreeAuthenticationHandler $treeAuthenticationHandler)
    {
        $this->treeAuthenticationHandler = $treeAuthenticationHandler;
    }

    public function setCarbonDataManager(CarbonDataManager $carbonDataManager)
    {
        $this->carbonDataManager = $carbonDataManager;
    }

    public function setTreeAccountHandler(TreeAccountHandler $treeAccountHandler)
    {
        $this->treeAccountHandler = $treeAccountHandler;
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

        $isTreeActivated = $settings->get('tree_activation');
        $isTreeKitActivated = $settings->get('tree_kit_activation');

        if ($isTreeKitActivated) {
            if ($this->treeAuthenticationHandler->isConnected()) {
                $isConnected = true;
                $server = $settings->get('tree_api_server');
                $url = $parameters["urls.bo_climatekit.$server"];

                $credentials['client_id'] = $settings->get('tree_client_id');
                $credentials['username'] = $settings->get('tree_client_username');

                $infos['carbon_data_overview'] = $this->getCarbonDataOverview();
                $infos['link_backoffice'] = "https://".$url;
                $infos['is_test_mode_activated'] = $settings->get('tree_test_mode');
                $infos['is_test_mode_expired'] = $this->treeAccountHandler->isTestModeExpired();
                $infos['is_mandate_signed'] = $this->treeAccountHandler->isMandateSigned();
            }
        }

        return $this->buildTemplateResponse('tree/block-tree')
            ->addData('connected', $isConnected)
            ->addData('treeActivated', $isTreeActivated)
            ->addData('treeKitInfos', $infos)
            ->addData('credentials', $credentials)
        ;
    }

    /**
     * @return TemplateResponseComponent
     * @throws Exception
     */
    public function displayProductsAction()
    {
        /** @var Settings $settings */
        $settings = $this->getSettings();

        $isTreeActivated = $settings->get('tree_kit_activation');

        return $this->buildTemplateResponse('tree/block-tree-products')
            ->addData('treeActivated', $isTreeActivated)
            ;
    }

    /**
     * @return RedirectionResponseComponent
     * @throws Exception
     */
    public function treeActivationAction()
    {
        $settings = $this->getSettings();

        $treeActivation = $settings->get('tree_activation');

        $settings->set('tree_activation', !$treeActivation);

        if ($treeActivation) {
            $this->success('actions.tree_activation.toggle.result.success.disabled');
        } else {
            $this->success('actions.tree_activation.toggle.result.success.enabled');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.home.display'));
    }

    /**
     * @return RedirectionResponseComponent
     * @throws Exception
     */
    public function treeProductsActivationAction()
    {
        $settings = $this->getSettings();

        $treeActivation = $settings->get('tree_kit_activation');

        $settings->set('tree_kit_activation', !$treeActivation);

        if ($treeActivation) {
            $this->success('actions.tree_activation.toggle.result.success.disabled');
        } else {
            $this->success('actions.tree_activation.toggle.result.success.enabled');
        }

        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl('backoffice.products.display'));
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getCarbonDataOverview()
    {
        $data = array();

        $data[] = array(
            'period' => 'day',
            'footprint' => ($this->carbonDataManager->getSumOfTheDay('footprint') * 1000),
            'carbon_offset' => $this->carbonDataManager->getSumOfTheDay('carbon_offset')
        );

        $data[] = array(
            'period' => 'week',
            'footprint' => ($this->carbonDataManager->getSumOfTheWeek('footprint') * 1000),
            'carbon_offset' => $this->carbonDataManager->getSumOfTheWeek('carbon_offset')
        );

        $data[] = array(
            'period' => 'month',
            'footprint' => ($this->carbonDataManager->getSumOfTheMonth('footprint') * 1000),
            'carbon_offset' => $this->carbonDataManager->getSumOfTheMonth('carbon_offset')
        );

        foreach ($data as $index => $value) {
            $data[$index]['footprint'] = $this->formatAmount($value['footprint']);
            $data[$index]['carbon_offset'] = $this->formatAmount($value['carbon_offset']);
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
