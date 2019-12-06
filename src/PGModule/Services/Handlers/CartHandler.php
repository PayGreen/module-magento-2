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
 */

/**
 * Class PGModuleServicesHandlersCartHandler
 * @package PGModule\Services\Handlers
 */
class PGModuleServicesHandlersCartHandler extends PGFrameworkFoundationsAbstractObject
{
    /** @var PGFrameworkServicesLogger */
    private $logger;

    public function __construct(PGFrameworkServicesLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param PGDomainInterfacesEntitiesOrderInterface $order
     * @todo Implements method.
     */
    public function rebuildCart(PGDomainInterfacesEntitiesOrderInterface $order)
    {
        $this->logger->error("PGModuleServicesHandlersCartHandler::rebuildCart should be implemented.");
    }
}
