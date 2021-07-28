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

namespace PGI\Module\PGForm\Interfaces\Fields;

use PGI\Module\PGForm\Interfaces\ElementInterface;
use PGI\Module\PGForm\Interfaces\FormatterInterface;
use PGI\Module\PGForm\Interfaces\ValidatorInterface;

/**
 * Interface BasicFieldInterface
 * @package PGForm\Interfaces\Fields
 */
interface BasicFieldInterface extends ElementInterface
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
     * @param ValidatorInterface $validator
     * @return self
     */
    public function addValidator(ValidatorInterface $validator);

    /**
     * @param FormatterInterface $formatter
     * @return self
     */
    public function setFormatter(FormatterInterface $formatter);

    /**
     * @return BasicFieldInterface|null
     */
    public function getParent();

    /**
     * @param BasicFieldInterface $parent
     */
    public function setParent(BasicFieldInterface $parent);

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
