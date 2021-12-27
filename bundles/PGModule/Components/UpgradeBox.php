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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGModule\Components;

use PGI\Module\PGFramework\Tools\Version as VersionTool;
use Exception;

/**
 * Class UpgradeBox
 * @package PGModule\Components
 */
class UpgradeBox
{
    /** @var string */
    private $version;

    /** @var UpgradeStage[]  */
    private $stages = array();

    /**
     * UpgradeStage constructor.
     * @throws Exception
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * @return array
     */
    public function getStages(): array
    {
        return $this->stages;
    }

    /**
     * @param UpgradeStage $stage
     * @return $this
     */
    public function addStage(UpgradeStage $stage)
    {
        $this->stages[] = $stage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    public function greaterThan($version)
    {
        return (VersionTool::compare($this->getVersion(), $version) === 1);
    }

    public function greaterOrEqualThan($version)
    {
        return (VersionTool::compare($this->getVersion(), $version) !== -1);
    }

    public function lesserThan($version)
    {
        return (VersionTool::compare($this->getVersion(), $version) === -1);
    }

    public function lesserOrEqualThan($version)
    {
        return (VersionTool::compare($this->getVersion(), $version) !== 1);
    }
}
