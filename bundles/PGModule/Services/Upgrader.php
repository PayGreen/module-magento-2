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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGModule\Services;

use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use PGI\Module\PGModule\Components\UpgradeBox;
use PGI\Module\PGModule\Components\UpgradeStage;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use PGI\Module\PGSystem\Tools\Collection as CollectionTool;
use Exception;

/**
 * Class Upgrader
 * @package PGModule\Services
 */
class Upgrader extends AbstractObject
{
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
        /** @var UpgradeBox[] $upgradeStages */
        $upgradeBoxes = $this->buildUpgradeBoxes($from, $to);

        /** @var UpgradeBox $upgradeBox */
        foreach ($upgradeBoxes as $upgradeBox) {
            $this->logger->info(
                "Running upgrade box : {$upgradeBox->getVersion()}"
            );

            foreach ($upgradeBox->getStages() as $upgradeStage) {
                /** @var UpgradeInterface $upgrade */
                $upgrade = $this->upgradeAggregator->getService($upgradeStage->getType());

                $this->logger->info(
                    "Running upgrade agent '{$upgradeStage->getType()}'."
                );

                try {
                    if ($upgrade->apply($upgradeStage)) {
                        $this->logger->notice("Upgrade agent '{$upgradeStage->getType()}' applied successfully.");
                    }
                } catch (Exception $exception) {
                    $text = "An error occurred during upgrade agent '{$upgradeStage->getType()}' execution : ";
                    $text .= $exception->getMessage();

                    $this->logger->error($text, $exception);
                }
            }
        }
    }

    /**
     * @param string $from
     * @param string $to
     * @return UpgradeBox[]
     * @throws Exception
     */
    protected function buildUpgradeBoxes($from, $to)
    {
        $upgradeBoxes = array();

        foreach ($this->upgrades as $version => $config) {
            $upgradeBox =$this->buildUpgradeBox($version, $config);

            if ($upgradeBox->greaterThan($from) && ($upgradeBox->lesserOrEqualThan($to))) {
                $upgradeBoxes[] = $upgradeBox;
            }
        }

        usort($upgradeBoxes, function (
            UpgradeBox $stage1,
            UpgradeBox $stage2
        ) {
            if ($stage1->lesserThan($stage2->getVersion())) {
                return -1;
            } elseif ($stage1->greaterThan($stage2->getVersion())) {
                return 1;
            }

            return 0;
        });

        return $upgradeBoxes;
    }

    /**
     * @param $version
     * @param $config
     * @return UpgradeBox
     * @throws Exception
     */
    protected function buildUpgradeBox($version, $config)
    {
        if (CollectionTool::isSequential($config)) {
            $rawStages = $config;
        } else {
            $rawStages = array($config);
        }

        $upgradeBox = new UpgradeBox($version);

        foreach ($rawStages as $rawStage) {
            $upgradeBox->addStage(new UpgradeStage($rawStage));
        }

        return $upgradeBox;
    }
}
