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

namespace PGI\Module\PGLog\Services\LogWriters;

use PGI\Module\PGFramework\Foundations\AbstractConfigurableService;
use PGI\Module\PGFramework\Services\Dumper;
use PGI\Module\PGLog\Interfaces\LogWriterFileInterface;
use PGI\Module\PGSystem\Services\Pathfinder;
use DateTime;
use Exception;
use SplFileObject;

/**
 * Class FileLogWriter
 * @package PGLog\Services\LogWriters
 */
class FileLogWriter extends AbstractConfigurableService implements LogWriterFileInterface
{
    const DEFAULT_FORMAT = "<datetime> | *<type>* | <text>";

    /** @var SplFileObject|null */
    private $handle;

    /** @var Dumper */
    private $dumper;

    /** @var Pathfinder */
    private $pathfinder;

    public function __construct(
        Dumper $dumper,
        Pathfinder $pathfinder
    ) {
        $this->dumper = $dumper;
        $this->pathfinder = $pathfinder;
    }

    /**
     * @inheridoc
     */
    public function write($type, $text, $data = null)
    {
        if ($this->handle === null) {
            $this->fileOpen();
        }

        $this->fileWrite($type, $text, $data);
    }

    /**
     * @inheridoc
     * @throws Exception
     */
    public function getFilePath()
    {
        return $this->pathfinder->toAbsolutePath($this->getConfig('file'));
    }

    /**
     * @inheridoc
     * @throws Exception
     */
    public function reset()
    {
        $this->handle = null;

        $this->fileOpen();
    }

    /**
     * @return void
     */
    protected function fileOpen()
    {
        try {
            $path = $this->getFilePath();

            $this->handle = new SplFileObject($path, 'a');

            if (!$this->handle->isWritable()) {
                throw new Exception("Log file is not writable : $path.");
            }

            $this->fileWrite('NOTICE', "Logging channel opened : '$path'.");
        } catch (Exception $exception) {
            $this->handle = null;
        }
    }

    protected function fileWrite($type, $text, $data = null)
    {
        if ($this->handle !== null) {
            $formattedLog = $this->formatLog($type, $text, $data);
            $this->handle->fwrite($formattedLog . PHP_EOL);
        }
    }

    protected function formatLog($type, $text, $data = null)
    {
        $dt = new DateTime();
        $datetime = $dt->format('Y-m-d H:i:s');

        if (!is_string($text)) {
            $data = $text;
            $text = '';
        }

        $basicLog = $this->getFormat();

        $basicLog = str_replace('<type>', $type, $basicLog);
        $basicLog = str_replace('<datetime>', $datetime, $basicLog);
        $basicLog = str_replace('<text>', $text, $basicLog);

        $log = $basicLog;

        if (!is_null($data)) {
            $formattedData = $this->dumper->toString($data);

            $log .= " | $formattedData";
        }

        return $log;
    }

    protected function getFormat()
    {
        $format = self::DEFAULT_FORMAT;

        if ($this->hasConfig('format')) {
            $format = $this->getConfig('format');
        }

        return $format;
    }
}
