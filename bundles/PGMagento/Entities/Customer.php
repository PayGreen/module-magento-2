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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGMagento\Entities;

use Magento\Sales\Model\Order as LocalOrder;
use PGI\Module\PGDatabase\Foundations\AbstractEntityWrapped;
use PGI\Module\PGMagento\Entities\Address;
use PGI\Module\PGMagento\Entities\Cart;
use PGI\Module\PGShop\Interfaces\Entities\CustomerEntityInterface;

/**
 * Class Cart
 *
 * @package PGMagento\Entities
 * @method LocalOrder getLocalEntity()
 */
class Customer extends AbstractEntityWrapped implements CustomerEntityInterface
{
    public function getFirstname()
    {
        return $this->getLocalEntity()->getCustomerFirstname();
    }

    public function getLastname()
    {
        return $this->getLocalEntity()->getCustomerLastname();
    }

    public function getEmail()
    {
        return $this->getLocalEntity()->getCustomerEmail();
    }

    public function getShippingAddress()
    {
        $shippingAddress = $this->getLocalEntity()->getShippingAddress();

        return new Address($shippingAddress);
    }

    public function getBillingAddress()
    {
        $billingAddress = $this->getLocalEntity()->getBillingAddress();

        return new Address($billingAddress);
    }
}
