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
 * Class PGFormFoundationsAbstractField
 * @package PGForm\Foundations
 */
abstract class PGFormFoundationsAbstractField extends PGFormFoundationsAbstractElement implements PGFormInterfacesFieldInterface
{
    private $value = null;

    /** @var PGFormInterfacesFormatterInterface */
    private $formatter;

    /** @var PGFormInterfacesValidatorInterface[] */
    private $validators = array();

    private $errors = array();

    /** @var PGFormInterfacesFieldInterface|null */
    private $parent = null;

    /** @inheritdoc */
    public function addValidator(PGFormInterfacesValidatorInterface $validator)
    {
        $this->validators[] = $validator;

        return $this;
    }

    /** @inheritDoc */
    public function setFormatter(PGFormInterfacesFormatterInterface $formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * @return PGFormInterfacesFieldInterface|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param PGFormInterfacesFieldInterface $parent
     */
    public function setParent(PGFormInterfacesFieldInterface $parent)
    {
        $this->parent = $parent;
    }

    public function getFormName()
    {
        if ($this->parent === null) {
            return $this->getName();
        } else {
            return $this->parent->getFormName() . '[' . $this->getName() . ']';
        }
    }

    public function getFieldPrimary()
    {
        if ($this->parent === null) {
            return $this->getName();
        } else {
            return $this->parent->getFieldPrimary() . '_' . $this->getName();
        }
    }

    /** @inheritdoc */
    public function getValue()
    {
        return $this->value;
    }

    /** @inheritdoc */
    public function setValue($value)
    {
        $this->resetErrors();

        $this->value = $this->format($value);

        return $this;
    }

    /** @inheritdoc */
    public function isValid()
    {
        if (!$this->hasErrors()) {
            $this->valid();
        }

        return !$this->hasErrors();
    }

    /** @inheritdoc */
    public function isRequired()
    {
        return $this->getConfig('required', false);
    }

    /** @inheritdoc */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return self
     */
    protected function resetErrors()
    {
        $this->errors = array();

        return $this;
    }

    /**
     * @return bool
     */
    protected function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * @param string $error
     * @return $this
     */
    protected function addError($error)
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @param array $errors
     * @return $this
     */
    protected function addErrors(array $errors)
    {
        $this->errors = array_merge($this->errors, $errors);

        return $this;
    }

    protected function format($value)
    {
        $this->errors = array();

        $value = $this->formatter->format($value);

        if (!$this->formatter->isValid()) {
            $this->addError($this->formatter->getError());
        }

        return $value;
    }

    protected function valid()
    {
        foreach ($this->validators as $validator) {
            if (!$validator->validate($this->getValue())->isValid()) {
                $this->addErrors($validator->getErrors());
            }
        }
    }

    public function buildView()
    {
        /** @var PGFormInterfacesFieldViewInterface $view */
        $view = parent::buildView();

        if (!$view instanceof PGFormInterfacesFieldViewInterface) {
            $details = "View must implements PGFormInterfacesFieldViewInterface.";
            throw new Exception(
                "Invalid view for current field : '{$this->getName()}'. $details"
            );
        }

        return $view->setField($this);
    }

    public function init()
    {
        // Override if necessary.
    }
}
