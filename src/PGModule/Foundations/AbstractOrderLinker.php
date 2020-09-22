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
 * @version   1.1.1
 */

use Magento\Framework\App\ObjectManager;

abstract class PGModuleFoundationsAbstractOrderLinker extends PGModuleFoundationsAbstractFrontLinker
{
    /** @var PGDomainServicesManagersOrderManager */
    private $orderManager;

    public function __construct(ObjectManager $objectManager, PGDomainServicesManagersOrderManager $orderManager)
    {
        parent::__construct($objectManager);

        $this->orderManager = $orderManager;
    }

    /**
     * @param array $data
     * @return PGDomainInterfacesEntitiesOrderInterface
     * @throws Exception
     */
    public function findOrder(array $data = array())
    {
        /** @var PGDomainInterfacesEntitiesOrderInterface $order */
        $order = null;

        if (array_key_exists('id_order', $data)) {
            $order = $this->orderManager->getByPrimary($data['id_order']);
        } elseif (!array_key_exists('order', $data)) {
            throw new Exception("Building order URL require order entity or order primary.");
        } elseif (!$data['order'] instanceof PGDomainInterfacesEntitiesOrderInterface) {
            throw new Exception("Building order URL require PGDomainInterfacesEntitiesOrderInterface entity.");
        } else {
            $order = $data['order'];
        }

        if ($order === null) {
            throw new Exception("Order not found.");
        }

        return $order;
    }
}

