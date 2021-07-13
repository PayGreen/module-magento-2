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
 * @version   2.1.1
 *
 */

/**
 * Class BOTreeActionsDisplayCarbonBotConfigurationForm
 * @package BOTree\Actions
 */
class BOTreeActionsDisplayCarbonBotConfigurationForm extends PGServerFoundationsAbstractAction
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function process()
    {
        /** @var PGServerComponentsResponsesTemplateResponse $output */
        $response = $this->buildTemplateResponse('tree/block-tree-preview', array(
            'color' => $this->getSettings()->get("tree_bot_color"),
            'position' => $this->getSettings()->get("tree_bot_side"),
            'corner' => $this->getSettings()->get("tree_bot_corner"),
            'isDetailsActivated' => "true",
            'detailsUrl' => $this->getSettings()->get('tree_details_url'),
            'carbonEmittedTotal' => 1.122,
            'carbonEmittedFromDigital' => 54,
            'carbonEmittedFromTransportation' => 646,
            'carbonEmittedFromProduct' => 0.422,
        ))->addData('form', $this->buildSettingsFormView());

        $response->addResource(new PGServerComponentsResourcesStyleFileResource('/css/tree-bot-main.css'));
        $response->addResource(new PGServerComponentsResourcesScriptFileResource('/js/page-tree-preview.js'));

        return $response;
    }

    /**
     * @return PGViewComponentsBox
     * @throws Exception
     */
    protected function buildSettingsFormView()
    {
        /** @var PGModuleServicesSettings $settings */
        $settings = $this->getSettings();

        /** @var PGSystemComponentsParameters $parameters */
        $parameters = $this->getParameters();

        $form_name = 'tree_bot';

        $keys = array_keys($parameters["form.definitions.$form_name.fields"]);

        $values = $settings->getArray($keys);

        /** @var PGFormServicesViewsFormView $view */
        $view = $this->buildForm($form_name, $values)->buildView();

        $url = $this->getLinkHandler()->buildBackOfficeUrl('backoffice.tree_bot_form.save');

        $view->setAction($url);

        return new PGViewComponentsBox($view);
    }
}
