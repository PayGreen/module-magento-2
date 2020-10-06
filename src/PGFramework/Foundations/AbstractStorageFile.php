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
 * @version   1.2.0
 */

/**
 * Class PGFrameworkFoundationsAbstractStorageFile
 * @package PGFramework\Foundations
 */
abstract class PGFrameworkFoundationsAbstractStorageFile extends PGFrameworkFoundationsAbstractStorage
{
    private $filename;

    /**
     * @return mixed
     */
    protected function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    protected function setFilename($filename)
    {
        $this->filename = $filename;
    }

    protected function loadFile()
    {
        $content = null;

        if (file_exists($this->getFilename())) {
            $content = file_get_contents($this->getFilename());
        }

        return $content;
    }

    protected function saveFile($content)
    {
        $handler = @fopen($this->getFilename(), "w+");

        if ($handler && flock($handler, LOCK_EX | LOCK_NB)) {
            ftruncate($handler, 0);
            fwrite($handler, $content);
            flock($handler, LOCK_UN);
        }
    }
}
