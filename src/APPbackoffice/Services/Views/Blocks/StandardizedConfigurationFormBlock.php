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
 * @version   1.2.5
 *
 */

class APPbackofficeServicesViewsBlocksStandardizedConfigurationFormBlock extends APPbackofficeFoundationsAbstractStandardizedBlock
{
    /** @var PGFormServicesFormBuilder $formBuilder */
    protected $formBuilder;

    /** @var PGFrameworkServicesSettings */
    protected $settings;

    /** @var PGFrameworkComponentsParameters */
    protected $parameters;

    /** @var PGServerServicesLinker */
    protected $linker;

    /** @var PGFrameworkComponentsBag */
    protected $config;

    /**
     * @param PGFormServicesFormBuilder $formBuilder
     */
    public function setFormBuilder(PGFormServicesFormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }

    /**
     * @param PGFrameworkServicesSettings $settings
     */
    public function setSettings(PGFrameworkServicesSettings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param PGFrameworkComponentsParameters $parameters
     */
    public function setParameters(PGFrameworkComponentsParameters $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param PGServerServicesLinker $linker
     */
    public function setLinker(PGServerServicesLinker $linker)
    {
        $this->linker = $linker;
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

        /** @var PGFormServicesViewsFormView $view */
        $view = $this->formBuilder->build($name, $values)->buildView();

        $url = $this->linker->buildBackOfficeUrl($data['action']);

        $view->setAction($url);

        return new PGViewComponentsBox($view);
    }
}
