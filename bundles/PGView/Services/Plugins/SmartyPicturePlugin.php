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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGView\Services\Plugins;

use PGI\Module\PGModule\Services\Handlers\StaticFileHandler;

/**
 * Class SmartyPicturePlugin
 * @package PGView\Services\Plugins
 */
class SmartyPicturePlugin
{
    /** @var StaticFileHandler */
    private $staticFileHandler;

    public function __construct(StaticFileHandler $staticFileHandler)
    {
        $this->staticFileHandler = $staticFileHandler;
    }

    public function buildPictureUrl($picture)
    {
        list($base, $filename) = explode(':', $picture, 2);

        return $this->staticFileHandler->getUrl("/pictures/$base/$filename");
    }
}
