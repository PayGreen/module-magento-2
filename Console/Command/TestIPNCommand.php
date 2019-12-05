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
use PGFrameworkServicesPathfinder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once PAYGREEN_BOOTSTRAP_SRC;

class TestIPNCommand extends Command
{
    protected $customerHelper;

    protected function getService($name)
    {
        return \PGFrameworkContainer::getInstance()->get($name);
    }

    protected function configure()
    {
        $this
            ->setName('paygreen:test:test-ipn')
            ->setDescription('Test last IPN.')
            ->addArgument('args', InputArgument::IS_ARRAY, 'Command arguments', [])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $args = $input->getArgument('args');

        if (count($args) < 1) {
            throw new Exception("Send-IPN command require at least one parameters : the attempted order state.");
        }

        $attempted_order_state = $args[0];
        $attempted_subtask_state = isset($args[1]) ? $args[1] : null;
        $attempted_task_state = isset($args[2]) ? $args[2] : null;

        try {
            /** @var PGFrameworkServicesPathfinder $pathfinder */
            $pathfinder = $this->getService('pathfinder');

            $lastPaymentResultPath = $pathfinder->toAbsolutePath('var', '/last-payment-result.json');

            $result = json_decode(file_get_contents($lastPaymentResultPath), true);

            $output->write("Expected order state '$attempted_order_state' : ");

            $equals = ($result['order'] === $attempted_order_state);
            $empties = (($attempted_order_state === '-') && empty($result['order']));

            if (!$equals && !$empties) {
                $error = "Found order state : '{$result['order']}'.";
                $output->writeln("<error>KO</error>. $error");
                $logger->alert("Process result error :", $result);
                throw new Exception($error);
            } else {
                $output->writeln("<info>OK</info>.");
            }


            if ($attempted_subtask_state) {
                $output->write("Expected subtask state '$attempted_subtask_state' : ");

                if ($result['subtask'] !== $attempted_subtask_state) {
                    $error = "Found subtask state : '{$result['subtask']}'.";
                    $output->writeln("<error>KO</error>. $error");
                    $logger->alert("Process result error :", $result);
                    throw new Exception($error);
                } else {
                    $output->writeln("<info>OK</info>.");
                }
            }


            if ($attempted_task_state) {
                $output->write("Expected task state '$attempted_task_state' : ");

                if ($result['task'] !== $attempted_task_state) {
                    $error = "Found task state : '{$result['task']}'.";
                    $output->writeln("<error>KO</error>. $error");
                    $logger->alert("Process result error :", $result);
                    throw new Exception($error);
                } else {
                    $output->writeln("<info>OK</info>.");
                }
            }


        } catch (Exception $exception) {
            $logger->critical("Error during execute 'paygreen:test:test-ipn' command.", $exception);

            throw $exception;
        }
    }
}