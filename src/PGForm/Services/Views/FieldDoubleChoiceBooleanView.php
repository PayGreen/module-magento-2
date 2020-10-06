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

class PGFormServicesViewsFieldDoubleChoiceBooleanView extends PGFormFoundationsAbstractFieldChoice
{
    public function getData()
    {
        $data = parent::getData();

        $this->formatDataChoices('horizontal_choices', $data);
        $this->formatDataChoices('vertical_choices', $data);

        if ($data['translate']) {
            array_walk($data['horizontal_choices'], array($this, 'translate'));
            array_walk($data['vertical_choices'], array($this, 'translate'));
        }

        $data['guideline'] = $this->buildGuideline($data['horizontal_choices'], $data['vertical_choices'], $data['axis'], $data['multiple']);

        return $data;
    }

    protected function formatDataChoices($name, array &$data)
    {
        if (!array_key_exists($name, $data)) {
            throw new Exception("FieldChoiceExpandedView require '$name' configuration key.");
        } elseif (is_string($data[$name])) {
            $data[$name] = $this->getSelectHandler()->getChoices($data[$name]);
        }
    }

    /**
     * @param array $hChoices
     * @param array $vChoices
     * @param string $axis
     * @param bool $multiple
     * @return array
     * @throws Exception
     */
    protected function buildGuideline(array $hChoices, array $vChoices, $axis, $multiple)
    {
        $guideline = array();

        $values = $this->getField()->getValue();

        if (!is_array($values)) {
            throw new Exception("Double choice field require array values.");
        } elseif (in_array($axis, array('both', 'inverted')) && !$multiple) {
            throw new Exception("Double choice field with both axis must be declared as multiple.");
        }

        $base_name = $this->getField()->getFormName();
        $multiple_name = $multiple ? '[]' : '';

        foreach (array_keys($vChoices) as $vChoice) {
            $guideline[$vChoice] = array();

            foreach (array_keys($hChoices) as $hChoice) {
                switch ($axis) {
                    case 'horizontal':
                        $name = "{$base_name}[$hChoice]$multiple_name";
                        $value = $vChoice;
                        if ($multiple) {
                            $checked = array_key_exists($hChoice, $values) && is_array($values[$hChoice]) && in_array($vChoice, $values[$hChoice]);
                        } else {
                            $checked = array_key_exists($hChoice, $values) && ($values[$hChoice] === $vChoice);
                        }
                        break;
                    case 'vertical':
                        $name = "{$base_name}[$vChoice]$multiple_name";
                        $value = $hChoice;
                        if ($multiple) {
                            $checked = array_key_exists($vChoice, $values) && is_array($values[$vChoice]) && in_array($hChoice, $values[$vChoice]);
                        } else {
                            $checked = array_key_exists($vChoice, $values) && ($values[$vChoice] === $hChoice);
                        }
                        break;
                    case 'both':
                        $name = "{$base_name}[$vChoice][$hChoice]";
                        $value = 1;
                        $checked = array_key_exists($vChoice, $values) && is_array($values[$vChoice]) && array_key_exists($hChoice, $values[$vChoice]) && ($values[$vChoice][$hChoice] === true);
                        break;
                    case 'inverted':
                        $name = "{$base_name}[$hChoice][$vChoice]";
                        $value = 1;
                        $checked = array_key_exists($hChoice, $values) && is_array($values[$hChoice]) && array_key_exists($vChoice, $values[$hChoice]) && ($values[$hChoice][$vChoice] === true);
                        break;
                    default:
                        throw new Exception("Unknown double-choice axis : '$axis'.");
                }

                $guideline[$vChoice][$hChoice] = array(
                    'name' => $name,
                    'value' => $value,
                    'checked' => $checked
                );
            }
        }

        return $guideline;
    }

    /**
     * @inheritDoc
     */
    protected function completeFieldAttributes(array $data)
    {
        $attr = array_key_exists('attr', $data) ? $data['attr'] : array();

        if ($data['multiple']) {
            $attr['type'] = 'checkbox';
        } else {
            $attr['type'] = 'radio';
        }

        if ($this->getField()->isRequired()) {
            $attr['required'] = 'required';
        }

        return $attr;
    }
}
