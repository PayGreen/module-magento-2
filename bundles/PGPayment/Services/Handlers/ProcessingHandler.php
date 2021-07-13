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
 * @version   2.1.1
 *
 */

/**
 * Class PGPaymentServicesHandlersProcessingHandler
 * @package PGPayment\Services\Handlers
 */
class PGPaymentServicesHandlersProcessingHandler
{
    /** @var PGPaymentServicesManagersProcessingManager */
    private $processingManager;

    /** @var PGShopServicesHandlersShopHandler */
    private $shopHandler;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(
        PGPaymentServicesManagersProcessingManager $processingManager,
        PGShopServicesHandlersShopHandler $shopHandler,
        PGModuleServicesLogger $logger
    ) {
        $this->processingManager = $processingManager;
        $this->shopHandler = $shopHandler;
        $this->logger = $logger;
    }

    /**
     * @param APIPaymentComponentsRepliesTransaction $transaction
     * @return PGPaymentInterfacesEntitiesProcessingInterface
     * @throws Exception
     */
    public function loadCachedProcessingResult(APIPaymentComponentsRepliesTransaction $transaction)
    {
        $reference = $this->getReference($transaction);

        /** @var PGPaymentInterfacesEntitiesProcessingInterface $processing */
        $processing = $this->processingManager->getSuccessedProcessingByReference($reference);

        if ($processing !== null) {
            $this->processingManager->addEcho($processing);
        }

        return $processing;
    }

    /**
     * @param PGPaymentComponentsTasksTransactionManagement $task
     * @param bool $isSuccess
     * @throws Exception
     */
    public function saveProcessing(PGPaymentComponentsTasksTransactionManagement $task, $isSuccess)
    {
        $this->processingManager->create(
            $this->getReference($task->getTransaction()),
            $isSuccess,
            $this->shopHandler->getCurrentShop(),
            $task,
            $task->getTransaction(),
            $task->getOrder(),
            $task->getOrderStateFrom()
        );
    }

    /**
     * @param APIPaymentComponentsRepliesTransaction $transaction
     * @return string
     */
    public function getReference(APIPaymentComponentsRepliesTransaction $transaction)
    {
        $pid = $transaction->getPid();

        if ($transaction->getRank() > 0) {
            $pid .= '-' . $transaction->getRank();
        }

        $pid .= '-' . $transaction->getResult()->getStatus();

        return $pid;
    }
}
