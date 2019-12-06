<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 */

/**
 * Class PGDomainFoundationsEntitiesAbstractCartItem
 * @package PGDomain\Foundations\Entities
 */
abstract class PGDomainFoundationsEntitiesAbstractCartItem extends PGFrameworkFoundationsAbstractEntityWrapped implements PGDomainInterfacesEntitiesCartItemInterface
{
    /** @var PGDomainInterfacesEntitiesProductInterface */
    private $product;

    /**
     * @inheritdoc
     */
    public function getProduct()
    {
        if (empty($this->product)) {
            $this->product = $this->preloadProduct();
        }

        return $this->product;
    }

    /**
     * @return PGDomainInterfacesEntitiesProductInterface
     */
    abstract protected function preloadProduct();
}
