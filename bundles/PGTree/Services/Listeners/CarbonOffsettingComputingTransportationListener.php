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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGTree\Services\Listeners;

use PGI\Module\APITree\Services\Facades\ApiFacade;
use PGI\Module\PGClient\Exceptions\Response as ResponseException;
use PGI\Module\PGIntl\Services\Translator;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGShop\Interfaces\Entities\AddressEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\CustomerEntityInterface;
use PGI\Module\PGShop\Interfaces\ShopableInterface;
use PGI\Module\PGSystem\Components\Parameters as ParametersComponent;
use PGI\Module\PGTree\Components\Events\CarbonOffsettingComputing as CarbonOffsettingComputingEventComponent;
use Exception;

/**
 * Class CarbonOffsettingComputingTransportationListener
 * @package PGTree\Services\Listeners
 */
class CarbonOffsettingComputingTransportationListener
{
    /** @var ApiFacade */
    private $treeAPIFacade;

    /** @var Translator $translator */
    private $translator;

    /** @var Settings */
    private $settings;

    /** @var ParametersComponent */
    private $parameters;

    /** @var Logger */
    private $logger;

    public function __construct(
        ApiFacade $treeAPIFacade,
        Translator $translator,
        Settings $settings,
        ParametersComponent $parameters,
        Logger $logger
    ) {
        $this->treeAPIFacade = $treeAPIFacade;
        $this->translator = $translator;
        $this->settings = $settings;
        $this->parameters = $parameters;
        $this->logger = $logger;
    }

    /**
     * @param CarbonOffsettingComputingEventComponent $event
     * @throws ResponseException
     * @throws Exception
     */
    public function listen(CarbonOffsettingComputingEventComponent $event)
    {
        try {
            $this->treeAPIFacade->removeTransportationCarbonEmission(
                $event->getCarbonOffsettingComputing()->getFingerPrintPrimary()
            );
        } catch (Exception $exception) {
            $this->logger->warning("Unable to delete transportation carbon emission : " . $exception->getMessage());
        }

        /** @var ShopableInterface $shopable */
        $shopable = $event->getShopable();

        /** @var CustomerEntityInterface $customer */
        $customer = $event->getCustomer();

        if ($shopable === null) {
            $this->logger->debug("Missing shopable to compute 'Transportation' footprint.");
            return;
        }

        if ($shopable->getShippingWeight() == 0) {
            $this->logger->warning("Zero weight to compute 'Transportation' footprint.");
            return;
        }

        /** @var AddressEntityInterface $shippingAddress */
        $shippingAddress = null;

        if ($shopable->getShippingAddress() !== null) {
            $shippingAddress = $shopable->getShippingAddress();
        } elseif (($customer !== null) && ($customer->getShippingAddress() !== null)) {
            $shippingAddress = $customer->getShippingAddress();
        } else {
            $this->logger->debug("Missing address to compute 'Transportation' footprint.");
            return;
        }

        try {
            $this->treeAPIFacade->addTransportationCarbonEmission(
                $event->getCarbonOffsettingComputing()->getFingerPrintPrimary(),
                $shopable->getShippingWeight(),
                1,
                array(
                    $this->getMerchantDepositAddress(),
                    $this->createTransportAddress(
                        $shippingAddress->getFullAddressLine(),
                        $shippingAddress->getZipCode(),
                        $shippingAddress->getCity(),
                        $shippingAddress->getCountry()
                    )
                ),
                array($this->createTransport($this->parameters['tree.transport.default']))
            );

            $this->logger->notice('Successfully set transport footprint.');
        } catch (Exception $exception) {
            $this->logger->error("Unable to add transportation carbon emission : " . $exception->getMessage());
        }
    }

    /**
     * @return object
     * @throws Exception
     */
    protected function getMerchantDepositAddress()
    {
        $address_data = array();

        $address_data['address'] = $this->settings->get('shipping_address_line_1');
        $address_data['zipCode'] = $this->settings->get('shipping_address_zipcode');
        $address_data['city'] = $this->settings->get('shipping_address_city');

        $shipping_address_line_2 = $this->settings->get('shipping_address_line_2');

        if (!empty($shipping_address_line_2)) {
            $address_data['address'] .= ', ' . $shipping_address_line_2;
        }

        $iso = $this->settings->get('shipping_address_country');
        $address_data['country'] = $this->translator->get("countries.$iso");

        foreach ($address_data as $key => $value) {
            if (empty($value)) {
                $this->logger->error("Merchant deposit address is invalid '$key' is empty : ", $value);
                throw new Exception("Merchant deposit address is invalid");
            }
        }

        return $this->createTransportAddress(
            $address_data['address'],
            $address_data['zipCode'],
            $address_data['city'],
            $address_data['country']
        );
    }

    protected function createTransportAddress($address, $zipCode, $city, $country)
    {
        $data = array(
            'address' => $address,
            'zipCode' => $zipCode,
            'city' => $city,
            'country' => $country
        );

        return (object) $data;
    }

    protected function createTransport($type)
    {
        $data = array(
            'uuidTransport' => $type
        );

        return (object) $data;
    }
}
