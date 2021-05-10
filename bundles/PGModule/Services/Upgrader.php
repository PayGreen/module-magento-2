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
 * @version   2.0.2
 *
 */

/**
 * Class PGModuleServicesUpgrader
 * @package PGModule\Services
 */
class PGModuleServicesUpgrader extends PGSystemFoundationsObject
{
    const DEFAULT_PRIORITY = 500;

    /** @var PGFrameworkComponentsAggregator */
    private $upgradeAggregator;

    /** @var PGModuleServicesLogger */
    private $logger;

    /** @var array */
    private $upgrades;

    /**
     * PGModuleServicesSettings constructor.
     * @param PGFrameworkComponentsAggregator $upgradeAggregator
     * @param PGModuleServicesLogger $logger
     * @param array $upgrades
     */
    public function __construct(
        PGFrameworkComponentsAggregator $upgradeAggregator,
        PGModuleServicesLogger $logger,
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
        /** @var PGModuleComponentsUpgrade[] $upgradeStages */
        $upgradeStages = $this->buildUpgradeList($from, $to);

        /** @var PGModuleComponentsUpgrade $upgradeStage */
        foreach ($upgradeStages as $upgradeStage) {
            /** @var PGModuleInterfacesUpgrade $upgrade */
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
     * @return PGModuleComponentsUpgrade[]
     * @throws Exception
     */
    protected function buildUpgradeList($from, $to)
    {
        $upgradeStages = array();

        foreach ($this->upgrades as $upgradeName => $upgradeConfig) {
            $upgradeStage = new PGModuleComponentsUpgrade($upgradeName, $upgradeConfig);

            if ($upgradeStage->greaterThan($from) && ($upgradeStage->lesserOrEqualThan($to))) {
                $upgradeStages[] = $upgradeStage;
            }
        }

        usort($upgradeStages, function (
            PGModuleComponentsUpgrade $stage1,
            PGModuleComponentsUpgrade $stage2
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
