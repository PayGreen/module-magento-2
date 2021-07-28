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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGForm\Services\Views\Fields;

use PGI\Module\PGForm\Interfaces\Fields\BasicFieldInterface;
use PGI\Module\PGForm\Interfaces\Views\FieldViewInterface;
use PGI\Module\PGView\Services\View;

/**
 * Class BasicFieldView
 * @package PGForm\Services\Views\Fields
 */
class BasicFieldView extends View implements FieldViewInterface
{
    /**
     * CSS classes
     */
    const CSS_ERRORS = 'pgform__field--has-error';
    const CSS_REQUIRED = 'pgform__field--required';
    
    /** @var BasicFieldInterface */
    private $field;

    public function setField(BasicFieldInterface $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return BasicFieldInterface
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
