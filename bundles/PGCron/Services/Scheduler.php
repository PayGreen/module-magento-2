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

namespace PGI\Module\PGCron\Services;

use Exception;
use PGI\Module\PGCron\Interfaces\CronTabInterface;
use PGI\Module\PGCron\Interfaces\CronTaskInterface;
use PGI\Module\PGCron\Services\Builders\CronTaskBuilder;
use PGI\Module\PGFramework\Components\Aggregator;
use PGI\Module\PGFramework\Foundations\AbstractService;

/**
 * Class Scheduler
 * @package PGFramework\Services
 */
class Scheduler extends AbstractService
{
    /** @var Aggregator */
    private $cronTabs;

    /** @var CronTaskBuilder */
    private $cronTaskBuilder;

    public function __construct(Aggregator $cronTabAggregator, CronTaskBuilder $cronTaskBuilder)
    {
        $this->cronTabs = $cronTabAggregator;
        $this->cronTaskBuilder = $cronTaskBuilder;
    }

    public function run()
    {
        $this->log()->debug("Running scheduler.");

        /**
         * @var string $name
         * @var CronTabInterface $cronTab
         */
        foreach ($this->cronTabs as $name => $cronTab) {
            $this->log()->debug("Working on crontab '$name'.");

            $outdatedTaskNames = $cronTab->getOutdated();

            foreach ($outdatedTaskNames as $outdatedTaskName) {
                $this->log()->info("Running cron task '$outdatedTaskName'.");

                try {
                    /** @var CronTaskInterface $cronTask */
                    $cronTask = $this->cronTaskBuilder->build($outdatedTaskName);

                    if ($cronTask->execute()) {
                        $this->log()->debug("Cron task '$outdatedTaskName' successfully executed.");
                    } else {
                        $this->log()->warning("Cron task '$outdatedTaskName' not successfully executed.");
                    }
                } catch(Exception $exception) {
                    $text = "An error occurred during cron task '$outdatedTaskName' execution : ";
                    $text .= $exception->getMessage();
                    $this->log()->error($text, $exception);
                }

                $cronTab->refresh($outdatedTaskName);
            }
        }
    }

    public function checkUp()
    {
        $result = array();

        /** @var CronTabInterface $cronTab */
        foreach ($this->cronTabs as $cronTab) {
            $result = array_merge($result, $cronTab->toArray());
        }

        return $result;
    }
}
