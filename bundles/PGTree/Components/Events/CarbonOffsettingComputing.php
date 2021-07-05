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

/**
 * Class PGTreeComponentsEventsCarbonOffsettingComputing
 * @package PGTree\Components\Events
 */
class PGTreeComponentsEventsCarbonOffsettingComputing extends PGModuleFoundationsEvent
{
    /** @var PGTreeComponentsCarbonOffsettingComputing */
    private $carbonOffsettingComputing;

    /** @var PGShopInterfacesShopable|null */
    private $shopable;

    /** @var PGShopInterfacesEntitiesCustomer|null */
    private $customer;

    /** @var PGShopInterfacesEntitiesCarrier|null */
    private $carrier;

    public function __construct(
        PGTreeComponentsCarbonOffsettingComputing $carbonOffsettingComputing,
        PGShopInterfacesShopable $shopable = null,
        PGShopInterfacesEntitiesCustomer $customer = null,
        PGShopInterfacesEntitiesCarrier $carrier = null
    ) {
        $this->carbonOffsettingComputing = $carbonOffsettingComputing;
        $this->shopable = $shopable;
        $this->customer = $customer;
        $this->carrier = $carrier;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CARBON_OFFSETTING_COMPUTING';
    }

    /**
     * @return PGTreeComponentsCarbonOffsettingComputing
     */
    public function getCarbonOffsettingComputing()
    {
        return $this->carbonOffsettingComputing;
    }

    /**
     * @return PGShopInterfacesShopable|null
     */
    public function getShopable()
    {
        return $this->shopable;
    }

    /**
     * @return PGShopInterfacesEntitiesCustomer|null
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return PGShopInterfacesEntitiesCarrier|null
     */
    public function getCarrier()
    {
        return $this->carrier;
    }
}
