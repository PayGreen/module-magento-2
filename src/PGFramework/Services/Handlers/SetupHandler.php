<?php
/**
 * 2014 - 2019 Watt Is It
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
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   0.3.3
 */

class PGFrameworkServicesHandlersSetupHandler extends PGFrameworkFoundationsAbstractObject
{
    const INSTALL = 1;
    const UPGRADE = 2;
    const ALL = 3;

    /** @var PGFrameworkServicesBroadcaster */
    private $broadcaster;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    /** @var PGFrameworkServicesSettings */
    private $settings;

    /** @var string|null */
    private $lastUpdate;

    public function __construct(PGFrameworkServicesBroadcaster $broadcaster, PGFrameworkServicesSettings $settings, PGFrameworkServicesLogger $logger)
    {
        $this->broadcaster = $broadcaster;
        $this->logger = $logger;
        $this->settings = $settings;

        $this->lastUpdate = $settings->get('last_update');
    }

    public function run($flags = self::ALL)
    {
        $result = false;

        if ($flags & self::INSTALL === self::INSTALL) {
            $result = $this->runInstall();
        }

        if (!$result && ($flags & self::UPGRADE === self::UPGRADE)) {
            $result = $this->runUprade();
        }

        return $result;
    }

    public function runInstall()
    {
        if (!$this->lastUpdate) {
            $this->install();
            return true;
        }
    }

    public function runUprade()
    {
        if (!$this->lastUpdate && ($this->lastUpdate !== PAYGREEN_MODULE_VERSION)) {
            $this->upgrade();
            return true;
        }
    }

    public function install()
    {
        $this->fire('install');
        $this->setLastUpdate(PAYGREEN_MODULE_VERSION);
    }

    public function upgrade()
    {
        $this->fire('upgrade');
        $this->setLastUpdate(PAYGREEN_MODULE_VERSION);
    }

    public function uninstall()
    {
        $this->fire('uninstall');
        $this->setLastUpdate(null);
    }

    protected function fire($type)
    {
        $newUpdate = PAYGREEN_MODULE_VERSION;

        $this->logger->notice("Paygreen $type : '$this->lastUpdate' -> '$newUpdate'.");

        $this->broadcaster->fire(new PGFrameworkComponentsEventsModuleEvent($type, $this->lastUpdate));
    }

    protected function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        $this->settings->set('last_update', $lastUpdate);
    }
}
