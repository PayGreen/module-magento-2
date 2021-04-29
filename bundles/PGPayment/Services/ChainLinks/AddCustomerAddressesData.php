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
 * Class PGPaymentServicesChainLinksAddCustomerAddressesData
 * @package PGPayment\Services\ChainLinks
 */
class PGPaymentServicesChainLinksAddCustomerAddressesData extends PGPaymentFoundationsPaymentCreationChainLink
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function process(PGPaymentComponentsPaymentProject $paymentProject)
    {
        /** @var PGShopInterfacesProvisionersPrePayment $prePaymentProvisionner */
        $prePaymentProvisionner = $paymentProject->getPrePaymentProvisionner();

        /** @var PGShopInterfacesEntitiesAddress $shippingAddress */
        $shippingAddress = $this->getShippingAddress($prePaymentProvisionner);

        /** @var PGShopInterfacesEntitiesAddress $shippingAddress */
        $billingAddress = $this->getBillingAddress($prePaymentProvisionner);


        $paymentProject['shippingAddress'] = array(
            'lastName' => $shippingAddress->getLastName(),
            'firstName' => $shippingAddress->getFirstName(),
            'address' => $shippingAddress->getFullAddressLine(),
            'zipCode' => $shippingAddress->getZipCode(),
            'city' => $shippingAddress->getCity(),
            'country' => $shippingAddress->getCountry()
        );

        $paymentProject['billingAddress'] = array(
            'lastName' => $billingAddress->getLastName(),
            'firstName' => $billingAddress->getFirstName(),
            'address' => $billingAddress->getFullAddressLine(),
            'zipCode' => $billingAddress->getZipCode(),
            'city' => $billingAddress->getCity(),
            'country' => $billingAddress->getCountry()
        );
    }

    /**
     * @param PGShopInterfacesProvisionersPrePayment $prePaymentProvisionner
     * @return PGShopInterfacesEntitiesAddress
     * @throws Exception
     */
    protected function getShippingAddress(PGShopInterfacesProvisionersPrePayment $prePaymentProvisionner)
    {
        try {
            return $prePaymentProvisionner->getShippingAddress();
        } catch (Exception $exception) {
            $this->getLogger()->error('An error occured during shipping address recovery.', $exception);
        }
    }

    /**
     * @param PGShopInterfacesProvisionersPrePayment $prePaymentProvisionner
     * @return PGShopInterfacesEntitiesAddress
     * @throws Exception
     */
    protected function getBillingAddress(PGShopInterfacesProvisionersPrePayment $prePaymentProvisionner)
    {
        try {
            return $prePaymentProvisionner->getBillingAddress();
        } catch (Exception $exception) {
            $this->getLogger()->error('An error occured during billing address recovery.', $exception);
        }
    }
}
