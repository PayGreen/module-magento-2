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
 * @version   2.1.0
 *
 */

/**
 * Class PGPaymentServicesManagersProcessingManager
 *
 * @package PGPayment\Services\Managers
 * @method PGPaymentInterfacesRepositoriesProcessingRepositoryInterface getRepository()
 */
class PGPaymentServicesManagersProcessingManager extends PGDatabaseFoundationsManager
{
    /**
     * @param string $reference
     * @return PGPaymentInterfacesEntitiesProcessingInterface|null
     */
    public function getSuccessedProcessingByReference($reference)
    {
        return $this->getRepository()->findSuccessedProcessingByReference($reference);
    }

    /**
     * @param string $reference
     * @param bool $isSuccess
     * @param PGShopInterfacesEntitiesShop $shop
     * @param PGPaymentComponentsTasksTransactionManagement $paymentValidationTask
     * @param APIPaymentComponentsRepliesTransaction $transaction
     * @param PGShopInterfacesEntitiesOrder|null $order
     * @param string $stateFrom
     * @return PGPaymentInterfacesEntitiesProcessingInterface
     * @throws Exception
     */
    public function create(
        $reference,
        $isSuccess,
        PGShopInterfacesEntitiesShop $shop,
        PGPaymentComponentsTasksTransactionManagement $paymentValidationTask,
        APIPaymentComponentsRepliesTransaction $transaction,
        PGShopInterfacesEntitiesOrder $order = null,
        $stateFrom = null
    ) {
        $taskStatus = $paymentValidationTask->getStatusName($paymentValidationTask->getStatus());

        $data = array(
            'id_shop' => $shop->id(),
            'reference' => $reference,
            'success' => $isSuccess,
            'status' => $taskStatus,
            'pid' => $transaction->getPid(),
            'pid_status' => $transaction->getResult()->getStatus(),
            'created_at' => time(),
            'echoes' => array(),
            'amount' => $transaction->getAmount()
        );

        if ($order !== null) {
            $data += array(
                'id_order' => $order->id(),
                'state_from' => $stateFrom,
                'state_to' => $order->getState()
            );
        }

        $processing = $this->getRepository()->create($data);

        $this->getRepository()->insert($processing);

        return $processing;
    }

    /**
     * @param PGPaymentInterfacesEntitiesProcessingInterface $processing
     * @return bool
     * @throws Exception
     */
    public function addEcho(PGPaymentInterfacesEntitiesProcessingInterface $processing)
    {
        $processing->addEcho(new DateTime());

        return $this->getRepository()->update($processing);
    }
}
