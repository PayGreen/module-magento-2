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

namespace PGI\Module\PGModule\Components;

use PGI\Module\PGFramework\Tools\Version as VersionTool;
use PGI\Module\PGModule\Services\Upgrader;
use Exception;

/**
 * Class UpgradeStage
 * @package PGModule\Components
 */
class UpgradeStage
{
    private $config = array();

    private $type;

    /**
     * UpgradeStage constructor.
     * @param array $data
     * @throws Exception
     */
    public function __construct(array $data)
    {
        if (!array_key_exists('type', $data)) {
            throw new Exception("UpgradeStage require 'type' parameter.");
        }

        $this->type = $data['type'];

        $this->config = array_key_exists('config', $data) ? $data['config'] : array();
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getConfig($key)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : null;
    }
}
