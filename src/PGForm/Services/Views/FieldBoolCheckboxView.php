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
 * @version   1.2.4
 *
 */

class PGFormServicesViewsFieldBoolCheckboxView extends PGFormServicesViewsFieldView
{
    protected function completeFieldAttributes(array $data)
    {
        $attr = array_key_exists('attr', $data) ? $data['attr'] : array();

        $attr['name'] = $this->getField()->getFormName();
        $attr['id'] = 'pg_field_' . $this->getField()->getFieldPrimary();
        $attr['value'] = 1;
        $attr['type'] = 'checkbox';

        if ($this->getField()->getValue()) {
            $attr['checked'] = 'checked';
        }

        if ($this->getField()->isRequired()) {
            $attr['required'] = 'required';
        }

        return $attr;
    }
}
