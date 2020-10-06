<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.2.0
 */

class PGFormServicesViewsFieldView extends PGViewServicesView implements PGFormInterfacesFieldViewInterface
{
    /**
     * CSS classes
     */
    const CSS_ERRORS = 'pgform__field--has-error';
    const CSS_REQUIRED = 'pgform__field--required';
    
    /** @var PGFormInterfacesFieldInterface */
    private $field;

    public function setField(PGFormInterfacesFieldInterface $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return PGFormInterfacesFieldInterface
     */
    public function getField()
    {
        return $this->field;
    }

    public function getData()
    {
        $data = parent::getData();

        $data['attr'] = $this->completeFieldAttributes($data);
        $data['name'] = $this->getField()->getName();
        $data['id'] = 'pg_block_' . $this->getField()->getFieldPrimary();
        $data['value'] = $this->getField()->getValue();
        $data['errors'] = $this->getField()->getErrors();
        $data['required'] = $this->getField()->isRequired();
        $data['fieldsetClasses'] = $this->getFieldsetClasses($data);

        return $data;
    }

    protected function getFieldsetClasses(array $data)
    {
        $fieldsetClasses = array();

        if (!empty($data['errors']) && is_array($data['errors']) && count($data['errors'])) {
            array_push($fieldsetClasses, self::CSS_ERRORS);
        }

        if (!empty($data['required']) && $data['required']) {
            array_push($fieldsetClasses, self::CSS_REQUIRED);
        }

        return $fieldsetClasses;
    }

    protected function completeFieldAttributes(array $data)
    {
        $attr = array_key_exists('attr', $data) ? $data['attr'] : array();

        $attr['name'] = $this->getField()->getFormName();
        $attr['id'] = 'pg_field_' . $this->getField()->getFieldPrimary();
        $attr['value'] = $this->getField()->getValue();

        if ($this->getField()->isRequired()) {
            $attr['required'] = 'required';
        }

        return $attr;
    }
}
