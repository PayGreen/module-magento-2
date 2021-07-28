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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGMagento\Services\Repositories;

use Magento\Checkout\Model\Cart as LocalCart;
use Magento\Quote\Model\Quote as LocalQuote;
use PGI\Module\PGMagento\Entities\Cart;
use PGI\Module\PGMagento\Foundations\AbstractMagentoRepository;
use PGI\Module\PGShop\Interfaces\Repositories\CartRepositoryInterface;

/**
 * Class CartRepository
 *
 * @package PGMagento\Services\Repositories
 *
 * @method LocalQuote createLocalEntity()
 *
 */
class CartRepository extends AbstractMagentoRepository implements CartRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findByPrimary($id)
    {
        $entity = $this->createLocalEntity();

        $entity->load($id);

        return ($entity->getId() !== null) ? $this->wrapEntity($entity) : null;
    }

    /**
     * @inheritDoc
     */
    public function findCurrentCart()
    {
        $cart = null;

        /** @var LocalCart $localCart */
        $localCart = $this->getService('magento')->get('\Magento\Checkout\Model\Cart');

        $localCartQuote = $localCart->getQuote();

        if ($localCartQuote !== null) {
            $cart = $this->wrapEntity($localCartQuote);
        }

        return $cart;
    }

    public function wrapEntity($localEntity)
    {
        return new Cart($localEntity);
    }
}
