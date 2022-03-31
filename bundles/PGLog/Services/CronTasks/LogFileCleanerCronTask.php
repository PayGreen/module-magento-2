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

namespace PGI\Module\PGLog\Services\CronTasks;

use PGI\Module\PGCron\Foundations\AbstractCronTaskInterface;
use PGI\Module\PGLog\Services\Handlers\LogFileHandler;

class LogFileCleanerCronTask extends AbstractCronTaskInterface
{
    /** @var LogFileHandler */
    private $logFileHandler;

    public function __construct(LogFileHandler $logFileHandler)
    {
        $this->logFileHandler = $logFileHandler;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $this->logFileHandler->clean();

        return true;
    }
}