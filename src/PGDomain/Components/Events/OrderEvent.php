<?php
/**
 * 2014 - 2020 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.2.0
 */

/**
 * Class PGDomainComponentsEventsOrderEvent
 * @package PGDomain\Components\Events
 */
class PGDomainComponentsEventsOrderEvent extends PGFrameworkFoundationsAbstractEvent
{
    /** @var string */
    private $name;

    /** @var string */
    private $pid;

    /** @var PGDomainInterfacesEntitiesOrderInterface */
    private $order;

    public function __construct($name, $pid, PGDomainInterfacesEntitiesOrderInterface $order)
    {
        $this->order = $order;
        $this->pid = $pid;
        $this->name = 'ORDER.' . strtoupper($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return PGDomainInterfacesEntitiesOrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getPid()
    {
        return $this->pid;
    }
}
