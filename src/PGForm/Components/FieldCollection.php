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
 * @version   1.2.5
 *
 */

class PGFormComponentsFieldCollection extends PGFormFoundationsAbstractField implements PGFormInterfacesFieldArrayInterface
{
    /** @var PGFormInterfacesFieldInterface[] */
    private $children = array();

    /** @var PGFormServicesFieldBuilder */
    private $fieldBuilder;

    /**
     * @param PGFormServicesFieldBuilder $fieldBuilder
     */
    public function setFieldBuilder(PGFormServicesFieldBuilder $fieldBuilder)
    {
        $this->fieldBuilder = $fieldBuilder;
    }

    public function init()
    {
        parent::init();

        if (empty($this->children)) {
            $this->addChild();
        }
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function addChild($value = null)
    {
        $childConfig = $this->getConfig('child');

        $index = count($this->children);

        $child = $this->fieldBuilder->build($index, $childConfig);
        $child->setParent($this);

        if ($value !== null) {
            $child->setValue($value);
        }

        $this->children[] = $child;

        return $this;
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

        foreach ($this->children as $child) {
            $value[] = $child->getValue();
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

        $this->children = array();

        foreach ($values as $value) {
            $this->addChild($value);
        }

        return $this;
    }
}
