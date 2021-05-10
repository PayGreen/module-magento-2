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
 * @version   2.0.2
 *
 */

use Magento\Checkout\Model\Cart;
use Magento\Quote\Model\Quote;

/**
 * Class PGMagentoProvisionersCheckoutProvisioner
 * @package PGMagento\Provisioners
 */
class PGMagentoProvisionersCheckoutProvisioner extends PGSystemFoundationsObject implements PGShopInterfacesProvisionersCheckout
{
    /** @var PGShopInterfacesEntitiesCart */
    private $cart;

    public function __construct()
    {
        /** @var Cart $localCart */
        $localCart = $this->getService('magento')->get('Magento\Checkout\Model\Cart');

        $localQuote = $localCart->getQuote();

        if ($localQuote instanceof Quote) {
            $this->cart = new PGMagentoEntitiesCart($localQuote);
        } else {
            throw new Exception('Invalid local quote.');
        }
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmount()
    {
        return $this->cart->getTotalCost();
    }

    /**
     * @inheritDoc
     */
    public function getTotalUserAmount()
    {
        $price = $this->cart->getTotalCost();

        return PGShopToolsPrice::toFloat($price);
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        return $this->cart->getItems();
    }

    /**
     * @inheritDoc
     */
    public function getCurrency()
    {
        return $this->cart->getCurrency();
    }
}
