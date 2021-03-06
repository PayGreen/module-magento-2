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
 * @version   1.2.5
 *
 */

/**
 * Class PGModuleEntitiesCartItem
 *
 * @package PGModule\Entities
 * @method Magento\Quote\Model\Quote\Item getLocalEntity()
 */
class PGModuleEntitiesCartItem extends PGDomainFoundationsEntitiesAbstractCartItem
{
    protected function hydrateFromLocalEntity($localEntity)
    {
        // Do nothing.
    }

    /**
     * @inheritdoc
     */
    public function getCost()
    {
        $cost = $this->getLocalEntity()->getRowTotalInclTax();

        return (int) (round((float) $cost, 2) * 100);
    }

    /**
     * @inheritdoc
     */
    public function getQuantity()
    {
        return $this->getLocalEntity()->getTotalQty();
    }

    /**
     * @inheritdoc
     */
    protected function preloadProduct()
    {
        /** @var PGDomainInterfacesRepositoriesProductRepositoryInterface $productRepository */
        $productRepository = $this->getService('repository.product');

        /** @var Magento\Catalog\Model\Product $localEntity */
        $localEntity = $this->getLocalEntity()->getProduct();

        return $productRepository->wrapEntity($localEntity);
    }
}
