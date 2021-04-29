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
 * @version   2.0.1
 *
 */

/**
 * Class PGServerServicesFactoriesStageFactory
 * @package PGServer\Services\Factories
 */
class PGServerServicesFactoriesStageFactory extends PGSystemFoundationsObject
{
    /** @var PGServerServicesFactoriesTriggerFactory */
    private $triggerFactory;

    /** @var PGModuleServicesLogger */
    private $logger;

    public function __construct(PGServerServicesFactoriesTriggerFactory $triggerFactory, PGModuleServicesLogger $logger)
    {
        $this->triggerFactory = $triggerFactory;
        $this->logger = $logger;
    }

    /**
     * @param array $config
     * @return PGServerComponentsStage
     * @throws Exception
     */
    public function buildStage(array $config)
    {
        /** @var PGServerComponentsTrigger|null $trigger */
        $trigger = null;

        if (array_key_exists('if', $config)) {
            $trigger = $this->triggerFactory->buildTrigger($config['if']);
        }

        if (!array_key_exists('do', $config)) {
            throw new Exception("Server Stage definition must contains 'do' key.");
        }

        /** @var PGServerComponentsStage $stage */
        $stage = new PGServerComponentsStage($config, $trigger);

        $stage->setLogger($this->logger);

        return $stage;
    }
}
