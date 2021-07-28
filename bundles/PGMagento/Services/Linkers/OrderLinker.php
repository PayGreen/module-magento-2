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

namespace PGI\Module\PGMagento\Services\Linkers;

use PGI\Module\PGMagento\Foundations\Linkers\AbstractOrderLinker;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use Exception;

class OrderLinker extends AbstractOrderLinker
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function buildUrl(array $data = array())
    {
        /** @var OrderEntityInterface $localOrder */
        $order = $this->findOrder($data);

        return $this->buildFrontUrl('sales/order/view', ['order_id' => $order->id()]);
    }
}
