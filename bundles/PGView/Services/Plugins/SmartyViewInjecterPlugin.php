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

namespace PGI\Module\PGView\Services\Plugins;

use PGI\Module\PGView\Services\Handlers\ViewHandler;
use Exception;

/**
 * Class ViewHandler
 * @package PGView\Services\Plugins
 */
class SmartyViewInjecterPlugin
{
    /** @var ViewHandler */
    private $viewHandler;

    public function __construct(ViewHandler $viewHandler)
    {
        $this->viewHandler = $viewHandler;
    }

    public function insertView(array $params)
    {
        if (!array_key_exists('name', $params)) {
            throw new Exception("SmartyViewInjecter require 'name' parameter.");
        }

        $name = $params['name'];
        unset($params['name']);

        $data = array_key_exists('data', $params) ? $params['data'] : $params;

        return $this->viewHandler->renderView($name, $data);
    }

    public function insertTemplate(array $params)
    {
        if (!array_key_exists('name', $params)) {
            throw new Exception("SmartyTemplateInjecter require 'name' parameter.");
        }

        $name = $params['name'];
        unset($params['name']);

        $data = array_key_exists('data', $params) ? $params['data'] : $params;

        return $this->viewHandler->renderTemplate($name, $data);
    }
}
