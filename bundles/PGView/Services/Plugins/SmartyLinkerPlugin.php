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
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGView\Services\Plugins;

use PGI\Module\PGServer\Services\Handlers\LinkHandler;

/**
 * Class SmartyLinkerPlugin
 * @package PGView\Services\Plugins
 */
class SmartyLinkerPlugin
{
    /** @var LinkHandler */
    private $linkHandler;

    public function __construct(LinkHandler $linkHandler)
    {
        $this->linkHandler = $linkHandler;
    }

    public function buildBackOfficeUrl($action = null, array $data = array())
    {
        return $this->linkHandler->buildBackOfficeUrl($action, $data);
    }

    public function buildFrontOfficeUrl($action = null, array $data = array())
    {
        return $this->linkHandler->buildFrontOfficeUrl($action, $data);
    }
}
