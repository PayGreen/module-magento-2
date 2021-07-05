<?php
/**
 * 2014 - 2021 Watt Is It
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
 * @copyright 2014 - 2021 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.1.0
 *
 */

/**
 * Class PGViewComponentsNullStream
 * @package PGView\Components
 */
class PGViewComponentsNullStream
{
    private $bin;

    private function exists_data($path, $store = false)
    {
        // Thrashing unused arguments
        $this->bin = array($path, $store);

        return false;
    }

    private function load_data()
    {
        return false;
    }

    private function save_data()
    {
    }

    public function stream_open($url, $mode, $options, &$opened_path)
    {
        // Thrashing unused arguments
        $this->bin = array($url, $mode, $options, $opened_path);

        return true;
    }

    public function stream_write($data)
    {
        // Thrashing unused arguments
        $this->bin = $data;

        return 0;
    }

    public function stream_read($length)
    {
        // Thrashing unused arguments
        $this->bin = $length;

        return '';
    }

    public function stream_eof()
    {
        return true;
    }

    public function stream_seek($offset, $whence = SEEK_SET)
    {
        // Thrashing unused arguments
        $this->bin = array($offset, $whence);

        return true;
    }

    public function stream_tell()
    {
        return 0;
    }

    public function stream_flush()
    {
        return true;
    }

    public function stream_lock()
    {
        return false;
    }

    public function stream_metadata($path, $option, $value)
    {
        // Thrashing unused arguments
        $this->bin = array($path, $option, $value);
        
        return false;
    }

    public function rename($oldName, $newName)
    {
        // Thrashing unused arguments
        $this->bin = array($oldName, $newName);

        return false;
    }

    public function unlink($path)
    {
        // Thrashing unused arguments
        $this->bin = $path;

        return false;
    }

    public function url_stat($path, $flags)
    {
        // Thrashing unused arguments
        $this->bin = array($path, $flags);

        return array();
    }

    public function mkdir($path, $mode, $options)
    {
        // Thrashing unused arguments
        $this->bin = array($path, $mode, $options);

        return true;
    }

    public function rmdir($path, $options)
    {
        // Thrashing unused arguments
        $this->bin = array($path, $options);

        return true;
    }

    public function dir_opendir($path, $options)
    {
        // Thrashing unused arguments
        $this->bin = array($path, $options);

        return true;
    }

    public function dir_closedir()
    {
        return true;
    }

    public function dir_rewinddir()
    {
        return true;
    }

    public function dir_readdir()
    {
        return false;
    }
}
