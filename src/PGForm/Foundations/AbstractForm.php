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
 * @version   1.2.2
 *
 */

abstract class PGFormFoundationsAbstractForm extends PGFormFoundationsAbstractElement implements ArrayAccess, PGFormInterfacesFormInterface
{
    /** @var PGFormInterfacesFieldInterface[] */
    private $fields = array();

    public function __construct($name, array $config, array $fields)
    {
        parent::__construct($name, $config);

        $this->fields = $fields;
    }

    /**
     * @inheritDoc
     */
    public function getKeys()
    {
        return array_keys($this->fields);
    }

    /**
     * @inheritDoc
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @inheritDoc
     */
    public function addField($name, PGFormInterfacesFieldInterface $field)
    {
        $this->fields[$name] = $field;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getField($name)
    {
        if (!$this->hasField($name)) {
            throw new Exception("Unknown form field : $name.");
        }

        return $this->fields[$name];
    }

    /**
     * @inheritDoc
     */
    public function getValue($name)
    {
        if (!$this->hasField($name)) {
            throw new Exception("Unknown form field : $name.");
        }

        return $this->fields[$name]->getValue();
    }

    /**
     * @inheritDoc
     */
    public function setValue($name, $value)
    {
        if (!$this->hasField($name)) {
            throw new Exception("Unknown form field : $name.");
        }

        $this->fields[$name]->setValue($value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setValues(array $values)
    {
        foreach ($values as $name => $value) {
            if ($this->hasField($name)) {
                $this->setValue($name, $value);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getValues()
    {
        $values = array();

        foreach ($this->fields as $name => $field) {
            $values[$name] = $field->getValue();
        }

        return $values;
    }

    /**
     * @inheritDoc
     */
    public function isValid()
    {
        foreach ($this->fields as $field) {
            if (!$field->isValid()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getErrors()
    {
        $errors = $this->getRootErrors();

        foreach ($this->fields as $field) {
            $errors = array_merge($errors, $field->getErrors());
        }

        return $errors;
    }

    /**
     * @inheritDoc
     * @todo Gérer les erreurs au niveau formulaire.
     */
    public function getRootErrors()
    {
        return array();
    }

    /**
     * @inheritDoc
     */
    public function hasField($name)
    {
        return array_key_exists($name, $this->fields);
    }

    /**
     * @return PGViewInterfacesViewInterface
     * @throws Exception
     */
    public function buildView()
    {
        /** @var PGFormInterfacesFormViewInterface $view */
        $view = parent::buildView();

        if (!$view instanceof PGFormInterfacesFormViewInterface) {
            throw new Exception("Invalid view for current form : '{$this->getName()}'. View must implements PGFormInterfacesFormViewInterface.");
        }

        return $view->setForm($this);
    }

    public function offsetGet($offset)
    {
        return $this->getValue($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->setValue($offset, $value);
    }

    public function offsetUnset($offset)
    {
        throw new Exception("Unable to remove form field.");
    }

    public function offsetExists($offset)
    {
        return $this->hasField($offset);
    }
}
