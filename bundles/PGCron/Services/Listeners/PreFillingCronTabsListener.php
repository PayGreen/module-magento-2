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

namespace PGI\Module\PGCron\Services\Listeners;

use PGI\Module\PGCron\Interfaces\CronTabInterface;
use PGI\Module\PGFramework\Components\Aggregator;
use PGI\Module\PGModule\Components\Events\Module as ModuleEventComponent;
use PGI\Module\PGLog\Interfaces\LoggerInterface;

/**
 * Class PreFillingCronTabsListener
 * @package PGCron\Services\Listeners
 */
class PreFillingCronTabsListener
{
    /** @var Aggregator */
    private $cronTabs;

    /** @var LoggerInterface */
    private $logger;

    private $bin;

    public function __construct(Aggregator $cronTabAggregator, LoggerInterface $logger)
    {
        $this->cronTabs = $cronTabAggregator;
        $this->logger = $logger;
    }

    public function listen(ModuleEventComponent $event)
    {
        $this->bin = $event;

        $this->logger->info("Init all uninitialized cron tasks.");

        /**
         * @var string $name
         * @var CronTabInterface $cronTab
         */
        foreach($this->cronTabs as $name => $cronTab) {
            $this->logger->debug("Init cron tasks in cron tab '$name'.");
            $cronTab->init();
        }
    }
}
