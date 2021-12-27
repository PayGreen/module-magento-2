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

namespace PGI\Module\PGMagento\Entities;

use Magento\Directory\Model\CountryFactory as LocalCountryFactory;
use Magento\Sales\Api\Data\OrderAddressInterface as LocalOrderAddressInterface;
use PGI\Module\PGDatabase\Foundations\AbstractEntityWrapped;
use PGI\Module\PGShop\Interfaces\Entities\AddressEntityInterface;
use PGI\Module\PGSystem\Services\Container;

/**
 * Class Address
 *
 * @package PGMagento\Entities
 * @method LocalOrderAddressInterface getLocalEntity()
 */
class Address extends AbstractEntityWrapped implements AddressEntityInterface
{
    protected function hydrateFromLocalEntity($localEntity)
    {
        // Do nothing.
    }

    public function getLastname()
    {
        return $this->getLocalEntity()->getLastname();
    }

    public function getFirstname()
    {
        return $this->getLocalEntity()->getFirstname();
    }

    public function getCountry()
    {
        $objectManager = Container::getInstance()->get('magento');

        /** @var LocalCountryFactory $countryFactory */
        $countryFactory = $objectManager->get('Magento\Directory\Model\CountryFactory');

        $countryId = $this->getLocalEntity()->getCountryId();

        $country = $countryFactory->create()->loadByCode($countryId);

        return $country->getName();
    }

    public function getAddressLineOne()
    {
        return $this->getLocalEntity()->getStreetLine(1);
    }

    public function getAddressLineTwo()
    {
        return $this->getLocalEntity()->getStreetLine(2);
    }

    public function getFullAddressLine()
    {
        $lineOne = $this->getAddressLineOne();

        $lineTwo = $this->getAddressLineTwo();

        $address = $lineOne;

        if (!empty($lineTwo)) {
            $address = $lineOne . ', ' . $lineTwo;
        }

        return $address;
    }

    public function getCity()
    {
        return $this->getLocalEntity()->getCity();
    }

    public function getZipCode()
    {
        return $this->getLocalEntity()->getPostcode();
    }
}
