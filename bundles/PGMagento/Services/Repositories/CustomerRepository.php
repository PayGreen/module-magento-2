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
 * @version   2.1.1
 *
 */

use Magento\Customer\Model\Customer;


/**
 * Class PGMagentoServicesRepositoriesCustomerRepository
 *
 * @package PGMagento\Services\Repositories
 *
 * @method Magento\Customer\Model\Customer createLocalEntity()
 *
 */
class PGMagentoServicesRepositoriesCustomerRepository extends PGMagentoFoundationsAbstractMagentoRepository implements PGShopInterfacesRepositoriesCustomer
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
    public function findCurrentCustomer()
    {
        $customer = null;

        /** @var Customer $localCustomer */
        $localCustomer = $this->getService('magento')->get('\Magento\Customer\Model\Customer');

        if ($localCustomer !== null) {
            $customer = $this->wrapEntity($localCustomer);
        }

        return $customer;
    }

    public function wrapEntity($localEntity)
    {
        return new PGMagentoEntitiesCustomer($localEntity);
    }
}
