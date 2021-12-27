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

namespace PGI\Module\BOModule\Services\Views\Blocks;

use PGI\Module\BOModule\Foundations\Blocks\AbstractStandardizedBlock;
use PGI\Module\PGForm\Services\Builders\FormBuilder;
use PGI\Module\PGForm\Services\Views\FormView;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGServer\Services\Handlers\LinkHandler;
use PGI\Module\PGSystem\Components\Bag as BagComponent;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;
use PGI\Module\PGView\Components\Box as BoxComponent;
use Exception;

/**
 * Class StandardizedConfigurationFormBlockView
 * @package BOModule\Services\Views\Blocks
 */
class StandardizedConfigurationFormBlockView extends AbstractStandardizedBlock
{
    /** @var FormBuilder $formBuilder */
    protected $formBuilder;

    /** @var Settings */
    protected $settings;

    /** @var ParametersComponent */
    protected $parameters;

    /** @var LinkHandler */
    protected $linkHandler;

    /** @var BagComponent */
    protected $config;

    /**
     * @param FormBuilder $formBuilder
     */
    public function setFormBuilder(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }

    /**
     * @param Settings $settings
     */
    public function setSettings(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param ParametersComponent $parameters
     */
    public function setParameters(ParametersComponent $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param LinkHandler $linkHandler
     */
    public function setLinkHandler(LinkHandler $linkHandler)
    {
        $this->linkHandler = $linkHandler;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function buildContent(array $data)
    {
        if (!$data['name']) {
            throw new Exception("Standardized configuration form block require 'name' parameter.");
        } elseif (!$data['action']) {
            throw new Exception("Standardized configuration form block require 'action' parameter.");
        }

        $name = $data['name'];

        $fields = $this->parameters["form.definitions.$name.fields"];

        if (empty($fields)) {
            throw new Exception("Form definition not found : '$name'.");
        }

        $keys = array_keys($fields);

        $values = $this->settings->getArray($keys);

        /** @var FormView $view */
        $view = $this->formBuilder->build($name, $values)->buildView();

        $url = $this->linkHandler->buildBackOfficeUrl($data['action']);

        $view->setAction($url);

        return new BoxComponent($view);
    }
}
