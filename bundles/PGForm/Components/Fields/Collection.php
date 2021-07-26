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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGForm\Components\Fields;

use PGI\Module\PGForm\Foundations\Fields\AbstractBasicField;
use PGI\Module\PGForm\Interfaces\Fields\BasicFieldInterface;
use PGI\Module\PGForm\Interfaces\Fields\CollectionFieldInterface;
use PGI\Module\PGForm\Services\Builders\FieldBuilder;
use Exception;

/**
 * Class Collection
 * @package PGForm\Components\Fields
 */
class Collection extends AbstractBasicField implements CollectionFieldInterface
{
    /** @var BasicFieldInterface[] */
    private $children = array();

    /** @var FieldBuilder */
    private $fieldBuilder;

    /**
     * @param FieldBuilder $fieldBuilder
     */
    public function setFieldBuilder(FieldBuilder $fieldBuilder)
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
