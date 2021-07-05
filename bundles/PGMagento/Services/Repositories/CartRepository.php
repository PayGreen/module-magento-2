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
 * @version   2.1.0
 *
 */

use Magento\Checkout\Model\Cart;


/**
 * Class PGMagentoServicesRepositoriesCartRepository
 *
 * @package PGMagento\Services\Repositories
 *
 * @method Magento\Quote\Model\Quote createLocalEntity()
 *
 */
class PGMagentoServicesRepositoriesCartRepository extends PGMagentoFoundationsAbstractMagentoRepository implements PGShopInterfacesRepositoriesCart
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

        /** @var Cart $localCart */
        $localCart = $this->getService('magento')->get('\Magento\Checkout\Model\Cart');

        $localCartQuote = $localCart->getQuote();

        if ($localCartQuote !== null) {
            $cart = $this->wrapEntity($localCartQuote);
        }

        return $cart;
    }

    public function wrapEntity($localEntity)
    {
        return new PGMagentoEntitiesCart($localEntity);
    }
}
