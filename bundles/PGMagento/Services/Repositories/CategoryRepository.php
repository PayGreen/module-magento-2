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
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGMagento\Services\Repositories;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as LocalCollectionFactory;
use Magento\Store\Model\StoreManagerInterface as LocalStoreManagerInterface;
use PGI\Module\PGMagento\Entities\Category;
use PGI\Module\PGMagento\Foundations\AbstractMagentoRepository;
use PGI\Module\PGShop\Interfaces\Repositories\CategoryRepositoryInterface;

class CategoryRepository extends AbstractMagentoRepository implements CategoryRepositoryInterface
{
    const ENTITY = 'Magento\Catalog\Model\Category';
    const RESOURCE = 'Magento\Catalog\Model\ResourceModel\Category';

    /**
     * @param Magento\Catalog\Model\Category $localEntity
     * @return Category
     */
    public function wrapEntity($localEntity)
    {
        return new Category($localEntity);
    }

    /**
     * @inheritdoc
     */
    public function findAll()
    {
        /** @var Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
        $collection = $this->getService('magento')->create('Magento\Catalog\Model\ResourceModel\Category\Collection');

        return $this->wrapEntities($collection);
    }

    /**
     * @inheritdoc
     */
    public function findAllByShop($id_shop)
    {
        /** @var StoreManagerInterface $storeManager */
        $storeManager = $this->getService('magento')->get('Magento\Store\Model\StoreManagerInterface');

        /** @var CollectionFactory $categoryFactory */
        $categoryFactory = $this->getService('magento')->create('Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');

        $collection = $categoryFactory->create()
            ->addAttributeToSelect('*')
            ->setStore($storeManager->getStore($id_shop))
        ;

        return $this->wrapEntities($collection);
    }
}
