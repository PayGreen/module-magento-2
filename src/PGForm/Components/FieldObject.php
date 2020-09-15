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

class PGFormComponentsFieldObject extends PGFormFoundationsAbstractField implements PGFormInterfacesFieldObjectInterface
{
    /** @var PGFormInterfacesFieldInterface[] */
    private $children = array();

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function addChild(PGFormInterfacesFieldInterface $child)
    {
        $childrenConfig = $this->getConfig('children', array());

        $name = $child->getName();

        if (!array_key_exists($name, $childrenConfig)) {
            throw new Exception("Unknown child name : '$name'.");
        }

        $this->children[$name] = $child;

        $child->setParent($this);

        return $this;
    }

    /** @inheritDoc */
    public function hasChild($name)
    {
        return array_key_exists($name, $this->children);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getChild($name)
    {
        if (!$this->hasChild($name)) {
            throw new Exception("Child not found : '$name'.");
        }

        return $this->children[$name];
    }

    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function getValue()
    {
        $value = array();
        $childrenConfig = $this->getConfig('children', array());

        foreach (array_keys($childrenConfig) as $name) {
            $child = $this->getChild($name);
            $value[$name] = $child->getValue();
        }

        return $value;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function setValue($value)
    {
        $values = $this->format($value);

        foreach ($values as $name => $value) {
            $child = $this->getChild($name);
            $child->setValue($value);
        }

        return $this;
    }
}
