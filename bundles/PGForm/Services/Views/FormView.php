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
 * Class PGFormServicesViewsFormView
 * @package PGForm\Services\Views
 */
class PGFormServicesViewsFormView extends PGViewServicesView implements PGFormInterfacesFormViewInterface
{
    /** @var PGFormComponentsForm */
    private $form;

    private $action;

    /**
     * @return PGFormComponentsForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param PGFormComponentsForm $form
     * @return self
     */
    public function setForm(PGFormComponentsForm $form)
    {
        $this->form = $form;

        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    public function getData()
    {
        $data = parent::getData();

        $attr = $this->completeFieldAttributes($data);

        $data['attr'] = $attr;
        $data['errors'] = $this->form->getRootErrors();

        $fieldViews = array();

        /**
         * @var string $key
         * @var PGFormFoundationsAbstractField $field
         */
        foreach ($this->form->getFields() as $key => $field) {
            $fieldViews[$key] = new PGViewComponentsBox($field->buildView());
        }

        $data['fields'] = $fieldViews;

        return $data;
    }

    protected function completeFieldAttributes(array $data)
    {
        $attr = array_key_exists('attr', $data) ? $data['attr'] : array();

        $attr['action'] = $this->action;
        $attr['id'] = 'pg_form_' . $this->form->getName();

        return $attr;
    }
}
