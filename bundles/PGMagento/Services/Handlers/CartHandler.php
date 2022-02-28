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

namespace PGI\Module\PGMagento\Services\Handlers;

use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class CartHandler
 * @package PGMagento\Services\Handlers
 */
class CartHandler extends AbstractObject
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param OrderEntityInterface $order
     * @return bool
     * @todo Check consistence of rebuilded cart
     */
    public function rebuildCart(OrderEntityInterface $order)
    {
        try {
            /** @var Magento\Sales\Model\Order $local_order */
            $local_order = $order->getLocalEntity();
            $order_items = $local_order->getItems();

            /** @var Magento\Checkout\Model\Cart $rebuilded_cart */
            $rebuilded_cart = $this->getService('magento')->get('\Magento\Checkout\Model\Cart');

            foreach ($order_items as $item) {
                $rebuilded_cart->addOrderItem($item);
            }

            $rebuilded_cart->save();

            $this->logger->debug('Cart successfully rebuilded.');
        } catch (Exception $exception) {
            $this->logger->error('An error occured during cart rebuilding');

            return false;
        }

        return true;
    }
}
