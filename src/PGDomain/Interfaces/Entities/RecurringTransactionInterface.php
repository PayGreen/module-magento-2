<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.2
 */

/**
 * Interface PGDomainInterfacesEntitiesRecurringTransactionInterface
 * @package PGDomain\Interfaces\Entities
 */
interface PGDomainInterfacesEntitiesRecurringTransactionInterface extends PGFrameworkInterfacesWrappedEntityInterface
{
    /**
     * @return int
     */
    public function id();

    /**
     * @return string
     */
    public function getPid();

    /**
     * @return int
     */
    public function getOrderPrimary();

    /**
     * @return PGDomainInterfacesEntitiesOrderInterface
     */
    public function getOrder();

    /**
     * @return string
     */
    public function getState();

    /**
     * @return string
     */
    public function getStateOrderBefore();

    /**
     * @return string
     */
    public function getStateOrderAfter();

    /**
     * @return string
     */
    public function getMode();

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @return int
     */
    public function getRank();

    /**
     * @return DateTime
     */
    public function getCreatedAt();
}
