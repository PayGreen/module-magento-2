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

namespace PGI\Module\PGMagentoPayment\Services\Officers;

use PGI\Module\APIPayment\Components\Replies\Transaction as TransactionReplyComponent;
use PGI\Module\PGMagentoPayment\Components\Provisioners\PostPayment as PostPaymentProvisionerComponent;
use PGI\Module\PGShop\Interfaces\Officers\PostPaymentOfficerInterface;
use PGI\Module\PGShop\Interfaces\Provisioners\PostPaymentProvisionerInterface;
use PGI\Module\PGShop\Services\Managers\OrderManager;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class PostPaymentOfficer
 * @package PGMagentoPayment\Services\Officers
 */
class PostPaymentOfficer extends AbstractObject implements PostPaymentOfficerInterface
{
    /**
     * @inheritdoc
     */
    public function getOrder(PostPaymentProvisionerInterface $provisioner)
    {
        /** @var OrderManager $orderManager */
        $orderManager = $this->getService('manager.order');

        return $orderManager->getByPrimary((int) $provisioner->getTransaction()->getOrderPrimary());
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function createOrder(PostPaymentProvisionerInterface $provisioner, $state)
    {
        throw new Exception("Magento does not require creation of order.");
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function buildPostPaymentProvisioner($pid, TransactionReplyComponent $transaction)
    {
        return new PostPaymentProvisionerComponent($pid, $transaction);
    }
}
