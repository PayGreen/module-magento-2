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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGDatabase\Services\Upgrades;

use PGI\Module\PGDatabase\Services\Handlers\DatabaseHandler;
use PGI\Module\PGModule\Components\Upgrade as UpgradeComponent;
use PGI\Module\PGModule\Interfaces\UpgradeInterface;
use Exception;

/**
 * Class DatabaseUpgrade
 * @package PGDatabase\Services\Upgrades
 */
class DatabaseUpgrade implements UpgradeInterface
{
    /** @var DatabaseHandler */
    private $databaseHandler;

    public function __construct(DatabaseHandler $databaseHandler)
    {
        $this->databaseHandler = $databaseHandler;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(UpgradeComponent $upgradeStage)
    {
        $script = $upgradeStage->getConfig('script');

        if (empty($script)) {
            throw new Exception("Database upgrade require 'script' parameter.");
        }

        $scripts = is_array($script) ? $script : array($script);

        foreach ($scripts as $script) {
            $result = $this->databaseHandler->runScript($script);

            if (!$result) {
                return false;
            }
        }

        return true;
    }
}
