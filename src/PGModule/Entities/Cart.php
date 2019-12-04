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
 * @version   0.3.3
 */

/**
 * Class PGModuleEntitiesCart
 *
 * @package PGModule\Entities
 * @method Magento\Checkout\Model\Cart getLocalEntity()
 */
class PGModuleEntitiesCart extends PGDomainFoundationsEntitiesAbstractCart
{
    protected function hydrateFromLocalEntity($localEntity)
    {
        // Do nothing.
    }

    public function id()
    {
        return (int) $this->getLocalEntity()->getQuote()->getId();
    }

    /**
     * @inheritdoc
     */
    public function getTotalCost()
    {
        $price = $this->getLocalEntity()->getQuote()->getGrandTotal();

        return PGDomainToolsPrice::toInteger($price);
    }

    /**
     * @inheritdoc
     */
    public function getShippingCost()
    {
        $price = $this->getLocalEntity()->getQuote()->getShippingAddress()->getShippingInclTax();

        return PGDomainToolsPrice::toInteger($price);
    }

    /**
     * @inheritdoc
     */
    protected function preloadItems()
    {
        $items = array();

        foreach ($this->getLocalEntity()->getItems() as $item) {
            $items[] = new PGModuleEntitiesCartItem($item);
        }

        return $items;
    }
}
