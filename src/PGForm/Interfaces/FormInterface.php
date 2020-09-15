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
 * @version   1.1.0
 */

/**
 * Interface PGFormInterfacesFormInterface
 * @package PGForm\Interfaces
 */
interface PGFormInterfacesFormInterface
{
    /**
     * @return string[]
     */
    public function getKeys();

    /**
     * @return PGFormInterfacesFieldInterface[]
     */
    public function getFields();

    /**
     * @param string $name
     * @param PGFormInterfacesFieldInterface $field
     * @return mixed
     */
    public function addField($name, PGFormInterfacesFieldInterface $field);

    /**
     * @param string $name
     * @return PGFormInterfacesFieldInterface
     */
    public function getField($name);

    /**
     * @param string $name
     * @return mixed
     */
    public function getValue($name);

    /**
     * @param string $name
     * @param mixed $value
     * @return self
     */
    public function setValue($name, $value);

    /**
     * @param array $values
     * @return self
     */
    public function setValues(array $values);

    /**
     * @return mixed[]
     */
    public function getValues();

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return string[]
     */
    public function getErrors();

    /**
     * @param string $name
     * @return bool
     */
    public function hasField($name);

    /**
     * @return PGFormInterfacesFormViewInterface
     */
    public function buildView();
}
