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

use DateTime;
use Exception;
use PGFrameworkServicesLogger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once PAYGREEN_BOOTSTRAP_SRC;

class SendIPNCommand extends Command
{
    protected $customerHelper;

    const TMP_FILE = '/tmp/fake-payment-data.json';

    protected function getService($name)
    {
        return \PGFrameworkContainer::getInstance()->get($name);
    }

    protected function configure()
    {
        $this
            ->setName('paygreen:test:send-ipn')
            ->setDescription('Send test IPN.')
            ->addArgument('args', InputArgument::IS_ARRAY, 'Command arguments', [])
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $args = $input->getArgument('args');

        if (count($args) < 1) {
            throw new Exception("Send-IPN command require at least one parameters : the state of the IPN.");
        }

        $status = $args[0];

        $pid = isset($args[1]) ? $args[1] : null;

        try {
            file_put_contents(self::TMP_FILE, json_encode([
                'status' => (string) $status
            ]));

            $hash = hash('md5', date_timestamp_get(new DateTime()));

            if($pid === null) {
                $pid = "tr" . $hash;
            } elseif (strlen($pid) === 2) {
                $pid .= $hash;
            }

            $url = "http://magento2.dde/index.php/pgfront/payment/validate?pid=" . $pid;
            $logger->debug("Send IPN to : ", $url);

            try {
                $tuCurl = curl_init();
                curl_setopt($tuCurl, CURLOPT_URL, $url);
                curl_setopt($tuCurl, CURLOPT_VERBOSE, 0);
                curl_setopt($tuCurl, CURLOPT_HEADER, 0);
                curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);


                curl_exec($tuCurl);

                if(curl_errno($tuCurl)){
                    throw new Exception("Curl error: " . curl_error($tuCurl));
                }

                curl_close($tuCurl);
            } catch (Exception $exception) {
                throw new Exception("Unable to send IPN request to URL : $url", 0, $exception);
            }

            unlink(self::TMP_FILE);
        } catch (Exception $exception) {
            $logger->critical("Error during execute 'paygreen:test:send-ipn' command.", $exception);

            throw $exception;
        }
    }
}