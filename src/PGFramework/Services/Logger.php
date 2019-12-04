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
 * @version   0.3.2
 */

/**
 * Class PGFrameworkServicesLogger
 * @package PGFramework\Services
 */
class PGFrameworkServicesLogger extends PGFrameworkFoundationsAbstractObject
{
    /** @var SplFileObject|null */
    private $handle;

    /** @var PGFrameworkServicesDumper */
    private $dumper;

    private $format = "*<type>* | <datetime> | <text>";

    public function __construct(PGFrameworkServicesDumper $dumper)
    {
        $this->dumper = $dumper;
    }

    /**
     * @param $path
     * @return $this
     */
    public function openHandle($path)
    {
        $this->handle = null;

        try {
            $this->handle = new SplFileObject($path, 'a');

            if (!$this->handle->isWritable()) {
                throw new Exception("Log file is not writable : $path.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }

        $this->debug("Logging channel opened : '$path'.");

        return $this;
    }

    /**
     * @param $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
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
        if (PAYGREEN_ENV === 'DEV') {
            $this->write('DEBUG', $text, $data);
        }
    }

    private function write($type, $text, $data = null)
    {
        if ($this->handle !== null) {
            $dt = new DateTime();
            $datetime = $dt->format('Y-m-d H:i:s');

            if (!is_string($text)) {
                $data = $text;
                $text = '';
            }

            $basicLog = $this->format;

            $basicLog = str_replace('<type>', $type, $basicLog);
            $basicLog = str_replace('<datetime>', $datetime, $basicLog);
            $basicLog = str_replace('<text>', $text, $basicLog);

            $log = $basicLog;

            if (!is_null($data)) {
                $formatedData = $this->dumper->toString($data);

                $log .= " | $formatedData";
            }

            $this->handle->fwrite($log . PHP_EOL);
        }
    }
}
