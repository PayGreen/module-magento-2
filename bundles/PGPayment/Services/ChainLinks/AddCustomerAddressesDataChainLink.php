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

namespace PGI\Module\PGPayment\Services\ChainLinks;

use PGI\Module\PGPayment\Components\PaymentProject as PaymentProjectComponent;
use PGI\Module\PGPayment\Foundations\AbstractPaymentCreationChainLink;
use PGI\Module\PGShop\Interfaces\Entities\AddressEntityInterface;
use PGI\Module\PGShop\Interfaces\Provisioners\PrePaymentProvisionerInterface;
use Exception;

/**
 * Class AddCustomerAddressesDataChainLink
 * @package PGPayment\Services\ChainLinks
 */
class AddCustomerAddressesDataChainLink extends AbstractPaymentCreationChainLink
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function process(PaymentProjectComponent $paymentProject)
    {
        /** @var PrePaymentProvisionerInterface $prePaymentProvisionner */
        $prePaymentProvisionner = $paymentProject->getPrePaymentProvisionner();

        /** @var AddressEntityInterface $shippingAddress */
        $shippingAddress = $this->getShippingAddress($prePaymentProvisionner);

        /** @var AddressEntityInterface $shippingAddress */
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
     * @param PrePaymentProvisionerInterface $prePaymentProvisionner
     * @return AddressEntityInterface
     * @throws Exception
     */
    protected function getShippingAddress(PrePaymentProvisionerInterface $prePaymentProvisionner)
    {
        try {
            return $prePaymentProvisionner->getShippingAddress();
        } catch (Exception $exception) {
            $this->getLogger()->error('An error occured during shipping address recovery.', $exception);
        }
    }

    /**
     * @param PrePaymentProvisionerInterface $prePaymentProvisionner
     * @return AddressEntityInterface
     * @throws Exception
     */
    protected function getBillingAddress(PrePaymentProvisionerInterface $prePaymentProvisionner)
    {
        try {
            return $prePaymentProvisionner->getBillingAddress();
        } catch (Exception $exception) {
            $this->getLogger()->error('An error occured during billing address recovery.', $exception);
        }
    }
}
