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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGTree\Components\Events;

use PGI\Module\PGModule\Foundations\AbstractEvent;
use PGI\Module\PGShop\Interfaces\Entities\CarrierEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\CustomerEntityInterface;
use PGI\Module\PGShop\Interfaces\ShopableInterface;
use PGI\Module\PGTree\Components\CarbonOffsettingComputing as CarbonOffsettingComputingComponent;

/**
 * Class CarbonOffsettingComputing
 * @package PGTree\Components\Events
 */
class CarbonOffsettingComputing extends AbstractEvent
{
    /** @var CarbonOffsettingComputingComponent */
    private $carbonOffsettingComputing;

    /** @var ShopableInterface|null */
    private $shopable;

    /** @var CustomerEntityInterface|null */
    private $customer;

    /** @var CarrierEntityInterface|null */
    private $carrier;

    public function __construct(
        CarbonOffsettingComputingComponent $carbonOffsettingComputing,
        ShopableInterface $shopable = null,
        CustomerEntityInterface $customer = null,
        CarrierEntityInterface $carrier = null
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
     * @return CarbonOffsettingComputingComponent
     */
    public function getCarbonOffsettingComputing()
    {
        return $this->carbonOffsettingComputing;
    }

    /**
     * @return ShopableInterface|null
     */
    public function getShopable()
    {
        return $this->shopable;
    }

    /**
     * @return CustomerEntityInterface|null
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return CarrierEntityInterface|null
     */
    public function getCarrier()
    {
        return $this->carrier;
    }
}
