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
 * @version   2.1.0
 *
 */

use Magento\Directory\Model\CountryFactory;
use Magento\Sales\Api\Data\OrderAddressInterface;

/**
 * Class PGMagentoEntitiesAddress
 *
 * @package PGMagento\Entities
 * @method OrderAddressInterface getLocalEntity()
 */
class PGMagentoEntitiesAddress extends PGDatabaseFoundationsEntityWrapped implements PGShopInterfacesEntitiesAddress
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
        $objectManager = PGSystemServicesContainer::getInstance()->get('magento');

        /** @var CountryFactory $countryFactory */
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
