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

namespace PGI\Module\PGServer\Components\Responses;

use PGI\Module\PGServer\Foundations\AbstractResponse;

/**
 * Class File
 * @package PGServer\Components\Responses
 */
class File extends AbstractResponse
{
    /** @var string|null */
    private $path;

    /** @var string|null */
    private $mime;

    /**
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @param string $mime
     * @return self
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }
}
