<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGMagento\Entities;

use PGI\Module\PGMagento\Entities\Address;
use PGI\Module\PGMagento\Entities\Carrier;
use PGI\Module\PGMagento\Entities\CartItem;
use PGI\Module\PGMagento\Entities\Currency;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGShop\Foundations\Entities\AbstractCartEntity;
use PGI\Module\PGShop\Tools\Price as PriceTool;

/**
 * Class Cart
 *
 * @package PGMagento\Entities
 * @method Magento\Quote\Model\Quote getLocalEntity()
 */
class Cart extends AbstractCartEntity
{
    protected function hydrateFromLocalEntity($localEntity)
    {
        // Do nothing.
    }

    public function id()
    {
        return (int) $this->getLocalEntity()->getId();
    }

    /**
     * @inheritdoc
     */
    public function getTotalCost()
    {
        $price = $this->getLocalEntity()->getGrandTotal();

        return PriceTool::toInteger($price);
    }

    /**
     * @inheritdoc
     */
    public function getShippingCost()
    {
        $price = $this->getLocalEntity()->getShippingAddress()->getShippingInclTax();

        return PriceTool::toInteger($price);
    }

    /**
     * @inheritdoc
     */
    public function getShippingWeight()
    {
        $weight = 0;

        foreach ($this->getItems() as $item) {
            $item_quantity = $item->getQuantity();
            $item_weight = $item->getProduct()->getWeight();
            if (!$item_weight) {
                $weight += (1 * $item_quantity);
            } else {
                $weight += ($item->getProduct()->getWeight() * $item_quantity);
            }
        }

        return $weight;
    }

    /**
     * @inheritdoc
     */
    public function getCurrency()
    {
        $currency = $this->getLocalEntity()->getCurrency();

        return new Currency($currency);
    }

    /**
     * @inheritdoc
     */
    protected function preloadItems()
    {
        $items = array();

        foreach ($this->getLocalEntity()->getItemsCollection() as $item) {
            $items[] = new CartItem($item);
        }

        return $items;
    }

    /**
     * @inheritdoc
     */
    public function getShippingAddress()
    {
        $shippingAddress = $this->getLocalEntity()->getShippingAddress();

        return new Address($shippingAddress);
    }

    /**
     * @inheritdoc
     */
    public function getBillingAddress()
    {
        $billingAddress = $this->getLocalEntity()->getBillingAddress();

        return new Address($billingAddress);
    }

    /**
     * @inheritdoc
     */
    public function getCarrier()
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        $carrierName = $this->getLocalEntity()->getShippingAddress()->getShippingMethod();

        if (!$carrierName) {
            $logger->warning('Local carrier name not found in Cart entity.');
            
            return null;
        } else {
            return new Carrier($carrierName);
        }
    }
}
