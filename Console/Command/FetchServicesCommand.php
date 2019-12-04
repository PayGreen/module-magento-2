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
 * @version   0.3.3
 */

namespace Paygreen\Payment\Console\Command;

use Exception;
use PGFrameworkServicesLogger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once PAYGREEN_BOOTSTRAP_SRC;

class FetchServicesCommand extends Command
{
    protected $customerHelper;

    protected function getService($name)
    {
        return \PGFrameworkContainer::getInstance()->get($name);
    }

    protected function configure()
    {
        $this
            ->setName('paygreen:fetch:services')
            ->setDescription('Fetch parameters.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        try {
            $definitions = \PGFrameworkContainer::getInstance()->getServiceDefinitions();

            ksort($definitions);

            foreach ($definitions as $service => $definition) {
                $class = isset($definition['class']) ? $definition['class'] : 'undefined';
                echo "\e[32;1m$service\e[0m : $class" . PHP_EOL;

                if (isset($definition['arguments'])) {
                    $arguments = $this->agregateArguments($definition['arguments']);
                    echo "    ~ Arguments: [$arguments]" . PHP_EOL;
                }

                if (isset($definition['calls'])) {
                    echo "    ~ Calls:" . PHP_EOL;
                    foreach ($definition['calls'] as $call) {
                        $method = $call['method'];
                        $arguments = $this->agregateArguments($call['arguments']);
                        echo "        ~ $method: [$arguments]" . PHP_EOL;
                    }
                }
            }
        } catch (Exception $exception) {
            $logger->critical("Error during execute 'paygreen:fetch:services' command.", $exception);

            throw $exception;
        }
    }

    function agregateArguments(array $arguments = array()) {
        $formatedArguments = array();

        foreach($arguments as $argument) {
            if (is_array($argument)) {
                $formatedArguments[] = '[...]';
            } elseif (substr($argument, 0, 1) === '@') {
                $formatedArguments[] = "\e[36m$argument\e[0m";
            } elseif (substr($argument, 0, 1) === '%') {
                $formatedArguments[] = "\e[33m$argument\e[0m";
            } elseif (substr($argument, 0, 1) === '$') {
                $formatedArguments[] = "\e[35m$argument\e[0m";
            } else {
                $formatedArguments[] = $argument;
            }
        }

        return join(', ', $formatedArguments);
    }
}