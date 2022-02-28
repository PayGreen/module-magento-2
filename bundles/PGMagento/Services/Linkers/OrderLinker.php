<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGMagento\Services\Linkers;

use Magento\Framework\UrlInterface;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use Exception;
use PGI\Module\PGShop\Services\Managers\OrderManager;

class OrderLinker extends MagentoLinker
{
    /** @var OrderManager */
    private $orderManager;

    public function __construct(UrlInterface $localUrlBuilder, OrderManager $orderManager)
    {
        parent::__construct($localUrlBuilder);

        $this->orderManager = $orderManager;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function buildUrl(array $data = array())
    {
        /** @var OrderEntityInterface $localOrder */
        $order = $this->findOrder($data);

        return $this->getLocalUrlBuilder()->getUrl('sales/order/view', ['order_id' => $order->id()]);
    }

    /**
     * @param array $data
     * @return OrderEntityInterface
     * @throws Exception
     */
    public function findOrder(array $data = array())
    {
        /** @var OrderEntityInterface $order */
        $order = null;

        if (array_key_exists('id_order', $data)) {
            $order = $this->orderManager->getByPrimary($data['id_order']);
        } elseif (!array_key_exists('order', $data)) {
            throw new Exception("Building order URL require order entity or order primary.");
        } elseif (!$data['order'] instanceof OrderEntityInterface) {
            throw new Exception("Building order URL require OrderEntityInterface entity.");
        } else {
            $order = $data['order'];
        }

        if ($order === null) {
            throw new Exception("Order not found.");
        }

        return $order;
    }
}
