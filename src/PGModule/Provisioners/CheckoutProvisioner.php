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
 * @version   1.2.1
 *
 */

use Magento\Checkout\Model\Cart;

/**
 * Class PGModuleProvisionersPrePaymentProvisioner
 * @package PGModule\Provisioners
 */
class PGModuleProvisionersCheckoutProvisioner extends PGFrameworkFoundationsAbstractObject implements PGDomainInterfacesCheckoutProvisionerInterface
{
    /** @var PGDomainInterfacesEntitiesCartInterface */
    private $cart;

    public function __construct()
    {
        /** @var Magento\Checkout\Model\Cart $localCart */
        $localCart = $this->getService('magento')->get('Magento\Checkout\Model\Cart');

        $this->cart = new PGModuleEntitiesCart($localCart);
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

        return PGDomainToolsPrice::toFloat($price);
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        return $this->cart->getItems();
    }
}
