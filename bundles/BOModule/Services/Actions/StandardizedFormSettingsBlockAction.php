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

namespace PGI\Module\BOModule\Services\Actions;

use PGI\Module\PGForm\Services\Views\FormView;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGServer\Foundations\AbstractAction;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;
use PGI\Module\PGView\Components\Box as BoxComponent;
use Exception;

/**
 * Class StandardizedFormSettingsBlockAction
 * @package BOModule\Services\Actions
 */
class StandardizedFormSettingsBlockAction extends AbstractAction
{
    const DEFAULT_TEMPLATE = 'blocks/form-default';

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function process()
    {
        $template = self::DEFAULT_TEMPLATE;

        if ($this->hasConfig('template')) {
            $template = $this->getConfig('template');
        }

        return $this->buildTemplateResponse($template)
            ->addData('form', $this->buildSettingsFormView())
        ;
    }

    /**
     * @return BoxComponent
     * @throws Exception
     */
    protected function buildSettingsFormView()
    {
        /** @var Settings $settings */
        $settings = $this->getSettings();

        /** @var ParametersComponent $parameters */
        $parameters = $this->getParameters();

        $form_name = $this->getConfig('form_name');
        $form_action = $this->getConfig('form_action');

        $keys = array_keys($parameters["form.definitions.$form_name.fields"]);

        $values = $settings->getArray($keys);

        /** @var FormView $view */
        $view = $this->buildForm($form_name, $values)->buildView();

        $url = $this->getLinkHandler()->buildBackOfficeUrl($form_action);

        $view->setAction($url);

        return new BoxComponent($view);
    }
}
