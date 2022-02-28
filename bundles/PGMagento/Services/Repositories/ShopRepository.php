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

use Magento\Store\Model\StoreManagerInterface as LocalStoreManagerInterface;
use Magento\Store\Model\Store as LocalStore;
use Magento\Store\Model\ResourceModel\Group\Collection as LocalCollection;
use PGI\Module\PGMagento\Entities\Shop;
use PGI\Module\PGMagento\Foundations\AbstractMagentoRepository;
use PGI\Module\PGShop\Interfaces\Repositories\ShopRepositoryInterface;
use Exception;

/**
 * Class ShopRepository
 * @package PGMagento\Services\Repositories
 *
 * @method Shop createWrappedEntity(array $data = array())
 */
class ShopRepository extends AbstractMagentoRepository implements ShopRepositoryInterface
{
    const ENTITY = 'Magento\Store\Model\Group';
    const RESOURCE = 'Magento\Store\Model\ResourceModel\Group';

    const FAKE_SHOP = array();

    public function wrapEntity($localEntity)
    {
        if (empty($localEntity)) {
            throw new Exception("Shop entity can't be created with empty local entity.");
        }

        return new Shop($localEntity);
    }

    /**
     * @inheritDoc
     * @todo Récupérer le vrai Shop courant.
     */
    public function findCurrent()
    {
        /** @var LocalStoreManagerInterface $storeManager */
        $storeManager = $this->getService('magento')->get('Magento\Store\Model\StoreManagerInterface');

        /** @var LocalStore $localStore */
        $localStore = $storeManager->getStore();

        return $this->wrapEntity($localStore->getGroup());
    }

    public function findAll()
    {
        /** @var LocalCollection $collection */
        $collection = $this->getService('magento')->create('Magento\Store\Model\ResourceModel\Group\Collection');

        return $this->wrapEntities($collection);
    }
}
