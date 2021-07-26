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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGShop\Interfaces\Officers;

use PGI\Module\APIPayment\Components\Replies\Transaction as TransactionReplyComponent;
use PGI\Module\PGShop\Interfaces\Entities\OrderEntityInterface;
use PGI\Module\PGShop\Interfaces\Provisioners\PostPaymentProvisionerInterface;

/**
 * Interface PostPaymentOfficerInterface
 * @package PGShop\Interfaces\Officers
 */
interface PostPaymentOfficerInterface
{
    /**
     * @param PostPaymentProvisionerInterface $provisioner
     * @return OrderEntityInterface|null
     */
    public function getOrder(PostPaymentProvisionerInterface $provisioner);

    /**
     * @param PostPaymentProvisionerInterface $provisioner
     * @param string $state
     * @return OrderEntityInterface|null
     */
    public function createOrder(PostPaymentProvisionerInterface $provisioner, $state);

    /**
     * @param string $pid
     * @param TransactionReplyComponent $transaction
     * @return PostPaymentProvisionerInterface
     */
    public function buildPostPaymentProvisioner($pid, TransactionReplyComponent $transaction);
}
