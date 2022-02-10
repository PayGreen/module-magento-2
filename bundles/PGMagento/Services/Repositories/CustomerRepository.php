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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGMagento\Services\Repositories;

use Magento\Customer\Model\Customer as LocalCustomer;
use PGI\Module\PGMagento\Entities\Customer;
use PGI\Module\PGMagento\Foundations\AbstractMagentoRepository;
use PGI\Module\PGShop\Interfaces\Repositories\CustomerRepositoryInterface;

/**
 * Class CustomerRepository
 *
 * @package PGMagento\Services\Repositories
 *
 * @method LocalCustomer createLocalEntity()
 *
 */
class CustomerRepository extends AbstractMagentoRepository implements CustomerRepositoryInterface
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

        /** @var LocalCustomer $localCustomer */
        $localCustomer = $this->getService('magento')->get('\Magento\Customer\Model\Customer');

        if ($localCustomer !== null) {
            $customer = $this->wrapEntity($localCustomer);
        }

        return $customer;
    }

    public function wrapEntity($localEntity)
    {
        return new Customer($localEntity);
    }
}
