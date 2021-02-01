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


class PGModuleServicesRepositoriesProductRepository extends PGModuleFoundationsAbstractMagentoRepository
{
    const ENTITY = 'Magento\Catalog\Model\Product';
    const RESOURCE = 'Magento\Catalog\Model\ResourceModel\Product';

    public function findAll()
    {
        /** @var Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->getService('magento')->create('Magento\Catalog\Model\ResourceModel\Product\Collection');

        return $this->wrapEntities($collection);
    }

    public function wrapEntity($localEntity)
    {
        return new PGModuleEntitiesProduct($localEntity);
    }
}
