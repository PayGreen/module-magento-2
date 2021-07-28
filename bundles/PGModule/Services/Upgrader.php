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

namespace PGI\Module\PGModule\Services;

use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use PGI\Module\PGModule\Components\Upgrade as UpgradeComponent;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGModule\Services\Settings;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class Upgrader
 * @package PGModule\Services
 */
class Upgrader extends AbstractObject
{
    const DEFAULT_PRIORITY = 500;

    /** @var AggregatorComponent */
    private $upgradeAggregator;

    /** @var Logger */
    private $logger;

    /** @var array */
    private $upgrades;

    /**
     * Settings constructor.
     * @param AggregatorComponent $upgradeAggregator
     * @param Logger $logger
     * @param array $upgrades
     */
    public function __construct(
        AggregatorComponent $upgradeAggregator,
        Logger $logger,
        array $upgrades
    ) {
        $this->upgradeAggregator = $upgradeAggregator;
        $this->logger = $logger;
        $this->upgrades = $upgrades;
    }

    /**
     * @param string $from
     * @param string $to
     * @throws Exception
     */
    public function upgrade($from, $to)
    {
        /** @var UpgradeComponent[] $upgradeStages */
        $upgradeStages = $this->buildUpgradeList($from, $to);

        /** @var UpgradeComponent $upgradeStage */
        foreach ($upgradeStages as $upgradeStage) {
            /** @var UpgradeInterface $upgrade */
            $upgrade = $this->upgradeAggregator->getService($upgradeStage->getType());

            $this->logger->info(
                "Running upgrade stage '{$upgradeStage->getName()}' with upgrade agent '{$upgradeStage->getType()}'."
            );

            try {
                if ($upgrade->apply($upgradeStage)) {
                    $this->logger->notice("Upgrade stage '{$upgradeStage->getName()}' applied successfully.");
                }
            } catch (Exception $exception) {
                $text = "An error occurred during upgrade stage '{$upgradeStage->getName()}' execution : ";
                $text .= $exception->getMessage();

                $this->logger->error($text, $exception);
            }
        }
    }

    /**
     * @param string $from
     * @param string $to
     * @return UpgradeComponent[]
     * @throws Exception
     */
    protected function buildUpgradeList($from, $to)
    {
        $upgradeStages = array();

        foreach ($this->upgrades as $upgradeName => $upgradeConfig) {
            $upgradeStage = new UpgradeComponent($upgradeName, $upgradeConfig);

            if ($upgradeStage->greaterThan($from) && ($upgradeStage->lesserOrEqualThan($to))) {
                $upgradeStages[] = $upgradeStage;
            }
        }

        usort($upgradeStages, function (
            UpgradeComponent $stage1,
            UpgradeComponent $stage2
        ) {
            if ($stage1->lesserThan($stage2->getVersion())) {
                return -1;
            } elseif ($stage1->greaterThan($stage2->getVersion())) {
                return 1;
            }

            if ($stage1->getPriority() === $stage2->getPriority()) {
                return 0;
            }

            return ($stage1->getPriority() < $stage2->getPriority()) ? -1 : 1;
        });

        return $upgradeStages;
    }
}
