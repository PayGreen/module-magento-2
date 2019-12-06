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
 */

namespace Paygreen\Payment\Block;

use DateTime;
use Paygreen\Payment\Foundations\AbstractTemplate;
use PGDomainServicesPaygreenFacade;
use PGFrameworkInterfacesModuleFacadeInterface;
use PGFrameworkServicesPathfinder;

class DisplayModuleSystem extends AbstractTemplate
{
    public function getLogData()
    {
        $files = array('module.log', 'api.log');

        $logs = array();

        /** @var PGFrameworkServicesPathfinder $pathfinder */
        $pathfinder = $this->getService('pathfinder');

        foreach($files as $file) {
            $filename = $pathfinder->toAbsolutePath('var', "/$file");
            $datetime = new DateTime();

            if (is_file($filename)) {
                $logs[] = array(
                    'name' => $file,
                    'size' => $this->getHumanFilesize(filesize($filename)),
                    'updatedAt' => $datetime->setTimestamp(filemtime($filename))
                );
            } else {
                $logs[] = array(
                    'name' => $file
                );
            }
        }


        return $logs;
    }

    private function getHumanFilesize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    public function getModuleVersion()
    {
        return PAYGREEN_MODULE_VERSION;
    }

    public function getFrameworkVersion()
    {
        /** @var PGDomainServicesPaygreenFacade $paygreenFacade */
        $paygreenFacade = $this->getService('paygreen.facade');

        return $paygreenFacade::VERSION;
    }

    public function getPlatformVersion()
    {
        /** @var PGFrameworkInterfacesModuleFacadeInterface $moduleFacade */
        $moduleFacade = $this->getService('facade.module');

        return $moduleFacade->getApplicationName() . ' - ' . $moduleFacade->getApplicationVersion();
    }

    public function getPHPVersion()
    {
        return PHP_VERSION;
    }

    public function getCurlVersion()
    {
        $curl_data = $this->getCurlData();

        return $curl_data['version'];
    }

    public function getSSLVersion()
    {
        $curl_data = $this->getCurlData();

        return $curl_data['ssl_version'];
    }

    private function getCurlData()
    {
        if (function_exists('curl_version')) {
            $curl_data = curl_version();
        } else {
            $curl_data = array(
                'version' => 'NA',
                'ssl_version' => 'NA'
            );
        }

        return $curl_data;
    }
}
