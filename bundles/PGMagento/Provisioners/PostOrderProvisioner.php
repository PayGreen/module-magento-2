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
 * @version   2.0.1
 *
 */

/**
 * Class PGMagentoProvisionersPostOrderProvisioner
 * @package PGMagento\Provisioners
 */
class PGMagentoProvisionersPostOrderProvisioner extends PGSystemFoundationsObject implements PGShopInterfacesProvisionersPostOrder
{
    /** @var PGShopInterfacesEntitiesOrder|null  */
    private $order = null;

    /** @var PGShopInterfacesEntitiesCarrier|null */
    private $carrier = null;

    public function __construct(PGShopInterfacesEntitiesOrder $order)
    {
        $this->order = $order;
        $this->loadCarrier();
    }

    /**
     * @return PGShopInterfacesEntitiesOrder|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return PGShopInterfacesEntitiesCustomer|null
     */
    public function getCustomer()
    {
        return $this->order->getCustomer();
    }

    /**
     * @return PGShopInterfacesEntitiesCarrier|null
     */
    public function getCarrier()
    {
        return $this->carrier;
    }

    protected function loadCarrier()
    {
        /** @var PGModuleServicesLogger $logger */
        $logger = $this->getService('logger');

        $carrierName = $this->order->getLocalEntity()->getShippingMethod();

        if (!$carrierName) {
            $logger->warning('Local carrier name not found.');
        } else {
            $this->carrier = new PGMagentoEntitiesCarrier($carrierName);
        }
    }
}
