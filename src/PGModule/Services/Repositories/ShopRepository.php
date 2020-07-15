<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.0
 */

use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\ResourceModel\Group\Collection;

/**
 * Class PGModuleServicesRepositoriesShopRepository
 * @package PGModule\Services\Repositories
 *
 * @method PGModuleEntitiesShop createWrappedEntity(array $data = array())
 */
class PGModuleServicesRepositoriesShopRepository extends PGModuleFoundationsAbstractMagentoRepository implements PGDomainInterfacesRepositoriesShopRepositoryInterface
{
    const ENTITY = 'Magento\Store\Model\Group';
    const RESOURCE = 'Magento\Store\Model\ResourceModel\Group';

    const FAKE_SHOP = array();

    public function wrapEntity($localEntity)
    {
        if (empty($localEntity)) {
            throw new Exception("Shop entity can't be created with empty local entity.");
        }

        return new PGModuleEntitiesShop($localEntity);
    }

    /**
     * @inheritDoc
     * @todo Récupérer le vrai Shop courant.
     */
    public function findCurrent()
    {
        /** @var StoreManagerInterface $storeManager */
        $storeManager = $this->getService('magento')->get('Magento\Store\Model\StoreManagerInterface');

        /** @var Store $localStore */
        $localStore = $storeManager->getStore();

        return $this->wrapEntity($localStore->getGroup());
    }

    public function findAll()
    {
        /** @var Collection $collection */
        $collection = $this->getService('magento')->create('Magento\Store\Model\ResourceModel\Group\Collection');

        return $this->wrapEntities($collection);
    }
}
