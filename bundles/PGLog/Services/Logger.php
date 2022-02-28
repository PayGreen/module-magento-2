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
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGLog\Services;

use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGLog\Interfaces\LogWriterInterface;
use PGI\Module\PGModule\Services\Handlers\BehaviorHandler;
use Exception;

/**
 * Class LoggerInterface
 * @package PGLog\Services
 */
class Logger implements LoggerInterface
{
    /** @var LogWriterInterface */
    private $logWriter;

    private $detailedLogActivated = null;

    private $detailedLogActivation = false;

    private $logs = array();

    /** @var BehaviorHandler */
    private $behaviorHandler;

    public function __construct(
        LogWriterInterface $logWriter
    ) {
        $this->logWriter = $logWriter;
    }

    /**
     * @param BehaviorHandler $behaviorHandler
     */
    public function setBehaviorHandler(BehaviorHandler $behaviorHandler)
    {
        $this->behaviorHandler = $behaviorHandler;
    }

    public function emergency($text, $data = null)
    {
        $this->write('EMERGENCY', $text, $data);
    }

    public function alert($text, $data = null)
    {
        $this->write('ALERT', $text, $data);
    }

    public function critical($text, $data = null)
    {
        $this->write('CRITICAL', $text, $data);
    }

    public function error($text, $data = null)
    {
        $this->write('ERROR', $text, $data);
    }

    public function warning($text, $data = null)
    {
        $this->write('WARNING', $text, $data);
    }

    public function notice($text, $data = null)
    {
        $this->write('NOTICE', $text, $data);
    }

    public function info($text, $data = null)
    {
        $this->write('INFO', $text, $data);
    }

    public function debug($text, $data = null)
    {
        $this->write('DEBUG', $text, $data);
    }

    private function isDetailedLogActivated()
    {
        if ($this->detailedLogActivated === null) {
            try {
                $this->detailedLogActivation = true;

                $this->detailedLogActivated = $this->behaviorHandler->get('detailed_logs');

                $this->detailedLogActivation = false;
            } catch (Exception $exception) {
                $this->detailedLogActivation = false;

                $this->detailedLogActivated = true;

                $this->error(
                    "An error occurred during detailed logs activation : " . $exception->getMessage(),
                    $exception
                );
            }
        }

        return (bool) $this->detailedLogActivated;
    }

    private function write($type, $text, $data = null)
    {
        $this->logs[] = array($type, $text, $data);

        if ($this->detailedLogActivation) {
            return;
        }

        if (($type !== 'DEBUG') || $this->isDetailedLogActivated()) {
            while (!empty($this->logs)) {
                list($type, $text, $data) = array_shift($this->logs);

                $this->logWriter->write($type, $text, $data);
            }
        }
    }
}
