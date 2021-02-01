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
 * @version   1.2.1
 *
 */

/**
 * Class PGDomainComponentsEventsOrderEvent
 * @package PGDomain\Components\Events
 */
class PGDomainComponentsEventsRefundEvent extends PGFrameworkFoundationsAbstractEvent
{
    /** @var PGDomainInterfacesEntitiesOrderInterface */
    private $order;

    /** @var float */
    private $amount;

    public function __construct(PGDomainInterfacesEntitiesOrderInterface $order, $amount = 0)
    {
        $this->order = $order;
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ORDER.REFUND';
    }

    /**
     * @return PGDomainInterfacesEntitiesOrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
