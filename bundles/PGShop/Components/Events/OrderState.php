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

namespace PGI\Module\PGShop\Components\Events;

use PGI\Module\PGModule\Foundations\AbstractEvent;
use PGI\Module\PGShop\Components\Events\Order as OrderEventComponent;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;

/**
 * Class OrderEventComponent
 * @package PGShop\Components\Events
 */
class OrderState extends AbstractEvent
{
    /** @var string */
    private $name;

    /** @var OrderEntityInterface */
    private $order;

    public function __construct(OrderEntityInterface $order)
    {
        $state = $order->getState();

        $this->order = $order;
        $this->name = 'ORDER.STATE.' . strtoupper($state);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return OrderEntityInterface
     */
    public function getOrder()
    {
        return $this->order;
    }
}
