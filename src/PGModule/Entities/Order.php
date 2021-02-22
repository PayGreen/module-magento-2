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

use Magento\Sales\Model\Order;

/**
 * Class PGModuleEntitiesOrder
 *
 * @package PGModule\Entities
 * @method Order getLocalEntity()
 */
class PGModuleEntitiesOrder extends PGFrameworkFoundationsAbstractEntityWrapped implements PGDomainInterfacesEntitiesOrderInterface
{
    /**
     * @inheritdoc
     */
    protected function hydrateFromLocalEntity($localEntity)
    {
        // Do nothing.
    }

    /**
     * @inheritdoc
     */
    public function id()
    {
        return $this->getLocalEntity()->getId();
    }

    /**
     * @inheritdoc
     */
    public function getReference()
    {
        return $this->getLocalEntity()->getIncrementId();
    }

    /**
     * @inheritdoc
     */
    public function getTotalAmount()
    {
        return PGDomainToolsPrice::toInteger($this->getLocalEntity()->getGrandTotal());
    }

    /**
     * @inheritdoc
     */
    public function getTotalUserAmount()
    {
        return PGDomainToolsPrice::fixFloat($this->getLocalEntity()->getGrandTotal());
    }

    /**
     * @inheritdoc
     */
    public function getCustomerId()
    {
        return $this->getLocalEntity()->getCustomerId();
    }

    /**
     * @inheritdoc
     */
    public function getCustomer()
    {
        /** @var PGDomainServicesManagersCustomerManager $customerManager */
        $customerManager = $this->getService('manager.customer');

        $customer = null;

        if ($this->getCustomerId() > 0) {
            $customer = $customerManager->getByPrimary($this->getCustomerId());
        }

        return $customer;
    }

    /**
     * @inheritdoc
     */
    public function getBillingAddress()
    {
        $localBillingAddress = $this->getLocalEntity()->getBillingAddress();

        return $localBillingAddress
            ? new PGModuleEntitiesAddress($localBillingAddress)
            : null;
    }

    public function getCustomerMail()
    {
        return $this->getLocalEntity()->getCustomerEmail();
    }

    public function getCurrency()
    {
        return $this->getLocalEntity()->getBaseCurrencyCode();
    }

    public function getState()
    {
        /** @var PGDomainServicesOrderStateMapper $orderStateMapper */
        $orderStateMapper = $this->getService('mapper.order_state');

        return $orderStateMapper->getOrderState(array(
            'state' => $this->getLocalEntity()->getState(),
            'status' => $this->getLocalEntity()->getStatus()
        ));
    }
}
