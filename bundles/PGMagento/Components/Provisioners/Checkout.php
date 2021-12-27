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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGMagento\Components\Provisioners;

use Magento\Checkout\Model\Cart as LocalCart;
use Magento\Quote\Model\Quote as LocalQuote;
use PGI\Module\PGMagento\Entities\Cart;
use PGI\Module\PGShop\Interfaces\Entities\CartEntityInterface;
use PGI\Module\PGShop\Interfaces\Provisioners\CheckoutProvisionerInterface;
use PGI\Module\PGShop\Tools\Price as PriceTool;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class Checkout
 * @package PGMagento\Components\Provisioners
 */
class Checkout extends AbstractObject implements CheckoutProvisionerInterface
{
    /** @var CartEntityInterface */
    private $cart;

    public function __construct()
    {
        /** @var LocalCart $localCart */
        $localCart = $this->getService('magento')->get('Magento\Checkout\Model\Cart');

        $localQuote = $localCart->getQuote();

        if ($localQuote instanceof LocalQuote) {
            $this->cart = new Cart($localQuote);
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

        return PriceTool::toFloat($price);
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
