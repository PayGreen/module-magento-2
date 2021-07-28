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
 * @version   2.3.0
 *
 */

namespace PGI\Module\PGSystem\Components\Storages;

use PGI\Module\PGSystem\Foundations\AbstractStorageFile;

/**
 * Class JSONFile
 * @package PGSystem\Components\Storages
 */
class JSONFile extends AbstractStorageFile
{
    public function __construct($filename)
    {
        $this->setFilename($filename);

        $this->loadData();
    }

    protected function loadData()
    {
        $content = $this->loadFile();

        if ($content !== null) {
            $data = json_decode($content, true);

            if (is_array($data)) {
                $this->setData($data);
            }
        }
    }

    protected function saveData()
    {
        $content = json_encode($this->getData());

        $this->saveFile($content);
    }
}
