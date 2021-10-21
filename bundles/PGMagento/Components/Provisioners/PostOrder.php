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
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGMagento\Components\Provisioners;

use PGI\Module\PGMagento\Entities\Carrier;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGShop\Interfaces\Entities\CarrierEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\CustomerEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Interfaces\Provisioners\PostOrderProvisionerInterface;
use PGI\Module\PGSystem\Foundations\AbstractObject;

/**
 * Class PostOrder
 * @package PGMagento\Components\Provisioners
 */
class PostOrder extends AbstractObject implements PostOrderProvisionerInterface
{
    /** @var OrderEntityInterface|null  */
    private $order = null;

    /** @var CarrierEntityInterface|null */
    private $carrier = null;

    public function __construct(OrderEntityInterface $order)
    {
        $this->order = $order;
        $this->loadCarrier();
    }

    /**
     * @return OrderEntityInterface|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return CustomerEntityInterface|null
     */
    public function getCustomer()
    {
        return $this->order->getCustomer();
    }

    /**
     * @return CarrierEntityInterface|null
     */
    public function getCarrier()
    {
        return $this->carrier;
    }

    protected function loadCarrier()
    {
        /** @var Logger $logger */
        $logger = $this->getService('logger');

        $carrierName = $this->order->getLocalEntity()->getShippingMethod();

        if (!$carrierName) {
            $logger->warning('Local carrier name not found.');
        } else {
            $this->carrier = new Carrier($carrierName);
        }
    }
}
