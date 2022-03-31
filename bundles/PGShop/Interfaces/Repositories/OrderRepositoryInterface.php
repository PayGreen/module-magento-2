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
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGShop\Interfaces\Repositories;

use PGI\Module\PGDatabase\Interfaces\RepositoryWrappedEntityInterface;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;

/**
 * Interface OrderRepositoryInterface
 * @package PGShop\Interfaces\Repositories
 */
interface OrderRepositoryInterface extends RepositoryWrappedEntityInterface
{
    /**
     * @param int $id
     * @return OrderEntityInterface|null
     */
    public function findByPrimary($id);

    /**
     * @param string $ref
     * @return OrderEntityInterface|null
     */
    public function findByReference($ref);

    /**
     * @param OrderEntityInterface $order
     * @return int
     */
    public function findRefundedAmount(OrderEntityInterface $order);

    /**
     * @param OrderEntityInterface $order
     * @param array $localState
     * @return mixed
     */
    public function updateOrderState(OrderEntityInterface $order, array $localState);
}
