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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGFramework\Components;

use Exception;
use SplFileObject;

/**
 * Class Picture
 * @package PGFramework\Components
 */
class Picture extends SplFileObject
{
    /**
     * @return string
     * @throws Exception
     */
    public function getMimeType()
    {
        $mimetype = null;

        switch ($this->getExtension()) {
            case 'gif':
                $mimetype = 'image/gif';
                break;
            case 'jpg':
            case 'jpeg':
                $mimetype = 'image/jpeg';
                break;
            case 'png':
                $mimetype = 'image/png';
                break;
            default:
                throw new Exception("Unsupported media file : '{$this->getExtension()}'.");
        }

        return $mimetype;
    }

    public function getContent()
    {
        return $this->fread($this->getSize());
    }
}
