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
 * Class BOTreeControllersPlugin
 * @package BOTree\Controllers
 */
class BOTreeControllersPlugin extends BOModuleFoundationsAbstractBackofficeController
{
    /** @var PGTreeServicesHandlersTreeAuthentication */
    private $treeAuthenticationHandler;

    /** @var PGTreeServicesManagersCarbonData */
    private $carbonDataManager;

    public function setTreeAuthenticationHandler(PGTreeServicesHandlersTreeAuthentication $treeAuthenticationHandler)
    {
        $this->treeAuthenticationHandler = $treeAuthenticationHandler;
    }

    public function setCarbonDataManager(PGTreeServicesManagersCarbonData $carbonDataManager)
    {
        $this->carbonDataManager = $carbonDataManager;
    }
    
    /**
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayAction()
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = $this->getSettings();

        /** @var PGSystemComponentsParameters */
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
                $credentials['server'] = 'data.tree_api_server.values.'.$server;

                $infos['carbon_data_overview'] = $this->getCarbonDataOverview();
                $infos['link_backoffice'] = "https://".$url;
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
     * @return PGServerComponentsResponsesTemplateResponse
     * @throws Exception
     */
    public function displayProductsAction()
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = $this->getSettings();

        $isTreeActivated = $settings->get('tree_kit_activation');

        return $this->buildTemplateResponse('tree/block-tree-products')
            ->addData('treeActivated', $isTreeActivated)
            ;
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
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
     * @return PGServerComponentsResponsesRedirectionResponse
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
