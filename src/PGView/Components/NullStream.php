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
 * @version   1.2.3
 *
 */

class PGViewComponentsNullStream
{
    private function exists_data($path, $store = false)
    {
        return false;
    }

    private function load_data()
    {
        return false;
    }

    private function save_data() {}

    public function stream_open($url, $mode, $options, &$opened_path)
    {
        return true;
    }

    public function stream_write($data)
    {
        return 0;
    }

    public function stream_read($length)
    {
        return '';
    }

    public function stream_eof()
    {
        return true;
    }

    public function stream_seek($offset, $whence = SEEK_SET)
    {
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
        return false;
    }

    public function rename($oldName, $newName)
    {
        return false;
    }

    public function unlink($path)
    {
        return false;
    }

    public function url_stat($path, $flags)
    {
        return array();
    }

    function mkdir($path, $mode, $options)
    {
        return true;
    }

    function rmdir($path, $options)
    {
        return true;
    }

    function dir_opendir($path, $options)
    {
        return true;
    }

    function dir_closedir()
    {
        return true;
    }

    function dir_rewinddir()
    {
        return true;
    }

    function dir_readdir()
    {
        return false;
    }
}