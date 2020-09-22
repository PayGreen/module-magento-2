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
 * @version   1.1.1
 */

/**
 * Interface PGFormInterfacesFieldInterface
 * @package PGForm\Interfaces
 */
interface PGFormInterfacesFieldInterface
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
    public function isValid();

    /**
     * @return bool
     */
    public function isRequired();

    /**
     * @return array
     */
    public function getErrors();

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
     * @return PGFormInterfacesFieldViewInterface
     */
    public function buildView();

    /**
     * @return void
     */
    public function init();
}
