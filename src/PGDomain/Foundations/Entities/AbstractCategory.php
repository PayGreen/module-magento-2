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
 * Class PGDomainFoundationsEntitiesAbstractCategory
 * @package PGDomain\Foundations\Entities
 */
abstract class PGDomainFoundationsEntitiesAbstractCategory extends PGFrameworkFoundationsAbstractEntityWrapped implements PGDomainInterfacesEntitiesCategoryInterface
{
    /** @var PGDomainInterfacesEntitiesCategoryInterface[] */
    private $children = array();

    /** @var null|PGDomainInterfacesEntitiesCategoryInterface */
    private $parent = null;

    /** @var array */
    private $paymentModes = array();

    /**
     * @inheritdoc
     */
    public function setParent(PGDomainInterfacesEntitiesCategoryInterface $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @inheritdoc
     */
    public function hasParent()
    {
        return ($this->parent !== null);
    }

    /**
     * @inheritdoc
     */
    public function addChild(PGDomainInterfacesEntitiesCategoryInterface $category)
    {
        $this->children[] = $category;
    }

    /**
     * @inheritdoc
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @inheritdoc
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }

    /**
     * @inheritdoc
     */
    public function getDepth()
    {
        $depth = 0;

        $current = $this;
        while (($current = $current->getParent()) !== null) {
            $depth ++;
        }

        return $depth;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentModes()
    {
        return $this->paymentModes;
    }

    /**
     * @inheritdoc
     */
    public function addPaymentMode($mode)
    {
        $this->paymentModes[] = $mode;
    }

    /**
     * @inheritdoc
     */
    public function hasPaymentMode($mode)
    {
        return in_array($mode, $this->paymentModes);
    }

    /**
     * @param string $string
     * @param string $delimiter
     * @return string
     * @todo Vérifier que iconv est toujours disponible, ou alors trouver une alternative.
     */
    protected function slugify($string, $delimiter = '-')
    {
        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        setlocale(LC_ALL, $oldLocale);
        return $clean;
    }
}
