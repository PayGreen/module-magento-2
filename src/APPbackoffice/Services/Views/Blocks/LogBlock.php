<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.1.1
 */

class APPbackofficeServicesViewsBlocksLogBlock extends PGViewServicesView
{
    /** @var PGFrameworkServicesPathfinder */
    protected $pathfinder;

    /**
     * APPbackofficeServicesViewsBlocksLogBlock constructor.
     */
    public function __construct(PGFrameworkServicesPathfinder $pathfinder)
    {
        $this->pathfinder = $pathfinder;

        $this->setTemplate('blocks/logs');
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        $data = parent::getData();

        $data['logs'] = $this->getLogData();

        return $data;
    }

    private function getFileList()
    {
        $files = array('module.log', 'api.log', 'view.log');

        if (PAYGREEN_ENV === 'DEV') {
            $files[] = 'error.log';
        }

        return $files;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getLogData()
    {
        $files = $this->getFileList();

        $logs = array();

        foreach ($files as $file) {
            $filename = $this->pathfinder->toAbsolutePath('log', "/$file");
            $datetime = new DateTime();

            if (is_file($filename)) {
                $logs[] = array(
                    'name' => $file,
                    'size' => $this->getHumanFilesize(filesize($filename)),
                    'updatedAt' => $datetime->setTimestamp(filemtime($filename))->format('d M Y H:i:s'),
                    'action' => true
                );
            } else {
                $logs[] = array(
                    'name' => $file,
                    'size' => "Log inexistant",
                    'updatedAt' => "NA",
                    'action' => false
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
}
