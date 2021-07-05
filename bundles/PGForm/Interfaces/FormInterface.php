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
 * Interface PGFormInterfacesFormInterface
 * @package PGForm\Interfaces
 */
interface PGFormInterfacesFormInterface extends PGFormInterfacesElementInterface
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
     * @param string $name
     * @return bool
     */
    public function hasField($name);
}
