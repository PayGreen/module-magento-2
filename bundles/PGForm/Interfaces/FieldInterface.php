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
 * Interface PGFormInterfacesFieldInterface
 * @package PGForm\Interfaces
 */
interface PGFormInterfacesFieldInterface extends PGFormInterfacesElementInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $value
     * @return self
     */
    public function setValue($value);

    /**
     * @return bool
     */
    public function isRequired();

    /**
     * @param PGFormInterfacesValidatorInterface $validator
     * @return self
     */
    public function addValidator(PGFormInterfacesValidatorInterface $validator);

    /**
     * @param PGFormInterfacesFormatterInterface $formatter
     * @return self
     */
    public function setFormatter(PGFormInterfacesFormatterInterface $formatter);

    /**
     * @return PGFormInterfacesFieldInterface|null
     */
    public function getParent();

    /**
     * @param PGFormInterfacesFieldInterface $parent
     */
    public function setParent(PGFormInterfacesFieldInterface $parent);

    /**
     * @return string
     */
    public function getFormName();

    /**
     * @return string
     */
    public function getFieldPrimary();

    /**
     * @return void
     */
    public function init();
}
