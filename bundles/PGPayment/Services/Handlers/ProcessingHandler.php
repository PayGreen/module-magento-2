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

namespace PGI\Module\PGPayment\Services\Handlers;

use PGI\Module\APIPayment\Components\Replies\Transaction as TransactionReplyComponent;
use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGPayment\Components\Tasks\TransactionManagement as TransactionManagementTaskComponent;
use PGI\Module\PGPayment\Interfaces\Entities\ProcessingEntityInterface;
use PGI\Module\PGPayment\Services\Managers\ProcessingManager;
use PGI\Module\PGShop\Services\Handlers\ShopHandler;
use Exception;

/**
 * Class ProcessingHandler
 * @package PGPayment\Services\Handlers
 */
class ProcessingHandler
{
    /** @var ProcessingManager */
    private $processingManager;

    /** @var ShopHandler */
    private $shopHandler;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        ProcessingManager $processingManager,
        ShopHandler $shopHandler,
        LoggerInterface $logger
    ) {
        $this->processingManager = $processingManager;
        $this->shopHandler = $shopHandler;
        $this->logger = $logger;
    }

    /**
     * @param TransactionReplyComponent $transaction
     * @return ProcessingEntityInterface
     * @throws Exception
     */
    public function loadCachedProcessingResult(TransactionReplyComponent $transaction)
    {
        $reference = $this->getReference($transaction);

        /** @var ProcessingEntityInterface $processing */
        $processing = $this->processingManager->getSuccessedProcessingByReference($reference);

        if ($processing !== null) {
            $this->processingManager->addEcho($processing);
        }

        return $processing;
    }

    /**
     * @param TransactionManagementTaskComponent $task
     * @param bool $isSuccess
     * @throws Exception
     */
    public function saveProcessing(TransactionManagementTaskComponent $task, $isSuccess)
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
     * @param TransactionReplyComponent $transaction
     * @return string
     */
    public function getReference(TransactionReplyComponent $transaction)
    {
        $pid = $transaction->getType() . $transaction->getPid();

        if ($transaction->getRank() > 0) {
            $pid .= '-' . $transaction->getRank();
        }

        $pid .= '-' . $transaction->getResult()->getStatus();

        return $pid;
    }
}
