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

namespace PGI\Module\PGCron\Services\Builders;

use Exception;
use PGI\Module\PGCron\Interfaces\CronTaskInterface;
use PGI\Module\PGFramework\Components\Aggregator;
use PGI\Module\PGFramework\Foundations\AbstractService;
use PGI\Module\PGSystem\Components\Bag;
use PGI\Module\PGSystem\Components\Parser;

class CronTaskBuilder extends AbstractService
{
    /** @var Aggregator */
    private $cronTasks;

    /** @var Parser */
    private $parser;

    public function __construct(Aggregator $cronTaskAggregator, Parser $parser)
    {
        $this->cronTasks = $cronTaskAggregator;
        $this->parser = $parser;
    }

    /**
     * @param $name
     * @return CronTaskInterface
     * @throws \PGI\Module\PGSystem\Exceptions\ParserConstant
     * @throws \PGI\Module\PGSystem\Exceptions\ParserParameter
     */
    public function build($name)
    {
        /** @var Bag $config */
        $config = $this->getCronTaskConfig($name);

        /** @var CronTaskInterface $service */
        $service = $this->cronTasks->getService($config['task']);

        if ($config['config']) {
            $service->addConfig($this->parser->parseConfig($config['config']));
        }

        return $service;
    }

    /**
     * @param string $name
     * @return Bag
     */
    protected function getCronTaskConfig($name)
    {
        $rawConfig = $this->getConfig("tasks.$name");

        if (!is_array($rawConfig)) {
            throw new Exception("Cron task '$name' does not have valid configuration.");
        }

        $config = new Bag($rawConfig);

        if (!$config['task']) {
            throw new Exception("Undefined service for cron task '$name'.");
        }

        return $config;
    }
}