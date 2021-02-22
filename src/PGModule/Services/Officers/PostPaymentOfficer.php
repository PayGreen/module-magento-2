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
 * Class PGModuleServicesOfficersPostPaymentOfficer
 * @package PGModule\Services\Officers
 */
class PGModuleServicesOfficersPostPaymentOfficer extends PGFrameworkFoundationsAbstractObject implements PGDomainInterfacesOfficersPostPaymentOfficerInterface
{
    /**
     * @inheritdoc
     */
    public function getOrder(PGDomainInterfacesPostPaymentProvisionerInterface $provisioner)
    {
        /** @var PGDomainServicesManagersOrderManager $orderManager */
        $orderManager = $this->getService('manager.order');

        return $orderManager->getByPrimary((int) $provisioner->getTransaction()->getOrderPrimary());
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function createOrder(PGDomainInterfacesPostPaymentProvisionerInterface $provisioner, $state)
    {
        throw new Exception("Magento does not require creation of order.");
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function buildPostPaymentProvisioner($pid, PGClientEntitiesPaygreenTransaction $transaction)
    {
        return new PGModuleProvisionersPostPaymentProvisioner($pid, $transaction);
    }
}
