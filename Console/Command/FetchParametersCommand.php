<?php
/**
 * 2014 - 2019 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.4
 */

namespace Paygreen\Payment\Console\Command;

use Exception;
use PGFrameworkServicesLogger;
use PGFrameworkToolsArray;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once PAYGREEN_BOOTSTRAP_SRC;

class FetchParametersCommand extends Command
{
    protected $customerHelper;

    protected function getService($name)
    {
        return \PGFrameworkContainer::getInstance()->get($name);
    }

    protected function configure()
    {
        $this
            ->setName('paygreen:fetch:parameters')
            ->setDescription('Fetch parameters.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        try {
            $parameters = \PGFrameworkContainer::getInstance()->getParameters()->toArray();

            ksort($parameters);

            $this->displayParameters($parameters);
        } catch (Exception $exception) {
            $logger->critical("Error during execute 'paygreen:fetch:parameters' command.", $exception);

            throw $exception;
        }
    }

    protected function displayParameters(array $parameters, $level = 0)
    {
        $dec = str_repeat(' ', $level * 2);

        foreach ($parameters as $key => $val) {
            if (is_array($val) && PGFrameworkToolsArray::isSequential($val)) {
                array_walk($val, array($this, 'formatValue'));
                $values = join(', ', $val);
                echo $dec . "\e[32;1m$key\e[0m : [$values]" . PHP_EOL;
            } elseif (is_array($val)) {
                echo $dec . "\e[32;1m$key\e[0m :" . PHP_EOL;
                $this->displayParameters($val, $level + 1);
            } else {
                $value = $this->formatValue($val);
                echo $dec . "\e[32;1m$key\e[0m : $value" . PHP_EOL;
            }
        }
    }

    public function formatValue($val)
    {
        $formatedValue = $val;

        if ($val === null) {
            $formatedValue = "\e[35mNull\e[0m";
        } elseif (is_bool($val)) {
            $value = $val ? 'TRUE' : 'FALSE';
            $formatedValue = "\e[33m$value\e[0m";
        } elseif (is_numeric($val)) {
            $formatedValue = "\e[36m$val\e[0m";
        } elseif (is_array($val)) {
            $formatedValue = "[...]";
        }

        return $formatedValue;
    }
}