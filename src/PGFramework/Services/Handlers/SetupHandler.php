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
 * @version   1.2.3
 *
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

    /** @var PGFrameworkInterfacesOfficersSetupOfficerInterface|null */
    private $setupOfficer = null;

    /** @var string|null */
    private $lastUpdate = null;

    /** @var PGFrameworkComponentsBag */
    private $config;

    /**
     * PGFrameworkServicesHandlersSetupHandler constructor.
     * @param PGFrameworkServicesBroadcaster $broadcaster
     * @param PGFrameworkServicesSettings $settings
     * @param PGFrameworkServicesLogger $logger
     * @param array $config
     * @throws Exception
     */
    public function __construct(
        PGFrameworkServicesBroadcaster $broadcaster,
        PGFrameworkServicesSettings $settings,
        PGFrameworkServicesLogger $logger,
        array $config
    ) {
        $this->broadcaster = $broadcaster;
        $this->logger = $logger;
        $this->settings = $settings;

        $this->config = new PGFrameworkComponentsBag($config);
    }

    /**
     * @param PGFrameworkInterfacesOfficersSetupOfficerInterface $setupOfficer
     */
    public function setSetupOfficer(PGFrameworkInterfacesOfficersSetupOfficerInterface $setupOfficer)
    {
        $this->setupOfficer = $setupOfficer;
    }

    /**
     * @param int $flag
     * @return bool
     * @throws Exception
     */
    public function run($flag = self::ALL)
    {
        $result = false;

        $this->logger->debug("Setup handler initialization with last update on '{$this->getLastUpdate()}'.");

        if (in_array($flag, array(self::INSTALL, self::ALL))) {
            $result = $this->runInstall();
        }

        if (!$result && in_array($flag, array(self::UPGRADE, self::ALL))) {
            $result = $this->runUpgrade();
        }

        return $result;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function runInstall()
    {
        if (!$this->getLastUpdate()) {
            $this->logger->debug("Installation is required.");
            $this->install();
            return true;
        } else {
            $this->logger->debug("Module already installed.");
        }

        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function runUpgrade()
    {
        $lastUpdate = $this->getLastUpdate();

        if (empty($lastUpdate)) {
            $this->logger->debug("Module not installed. Update not necessary.");
        } elseif ($lastUpdate === PAYGREEN_MODULE_VERSION) {
            $this->logger->debug("Module already up to date.");
        } else {
            $this->logger->debug("Update is required.");
            $this->upgrade();
            return true;
        }

        return false;
    }

    /**
     * @param string $version
     * @return bool
     * @throws Exception
     * @todo Verify if method is not utilised by any module and remove it.
     */
    public function runDelayedUpgrade($version)
    {
        $this->logger->notice("Setup handler delayed update : '$version'.");

        $this->setLastUpdate($version);

        if ($version === PAYGREEN_MODULE_VERSION) {
            $this->upgrade();
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function install()
    {
        $this->fire('install', PAYGREEN_MODULE_VERSION);
        $this->setLastUpdate(PAYGREEN_MODULE_VERSION);
    }

    /**
     * @throws Exception
     */
    public function upgrade()
    {
        $this->fire('upgrade', PAYGREEN_MODULE_VERSION);
        $this->setLastUpdate(PAYGREEN_MODULE_VERSION);
    }

    /**
     * @throws Exception
     */
    public function uninstall()
    {
        $this->fire('uninstall');
        $this->setLastUpdate(null);
    }

    /**
     * @param string $type
     * @param string|null $to
     * @throws Exception
     */
    protected function fire($type, $to = null)
    {
        $from = $this->getLastUpdate();

        $txtFrom = $from ? " from '$from'" : '';
        $txtTo = $to ? " to '$to'" : '';

        $this->logger->notice("PayGreen {$type}{$txtFrom}{$txtTo}.");

        $this->broadcaster->fire(new PGFrameworkComponentsEventsModuleEvent($type, $from));
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isLatest()
    {
        return ($this->getLastUpdate() === PAYGREEN_MODULE_VERSION);
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function getLastUpdate()
    {
        if ($this->lastUpdate === null) {
            $this->lastUpdate = $this->buildLastUpdate();
        }

        return $this->lastUpdate;
    }

    /**
     * @param string $lastUpdate
     * @throws Exception
     */
    protected function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        $this->settings->set('last_update', $lastUpdate);
    }

    /**
     * @return string|null
     * @throws Exception
     * @todo Remove deprecation management.
     */
    protected function buildLastUpdate()
    {
        $lastUpdate = (string) $saveUpdate = $this->settings->get('last_update');

        if (empty($lastUpdate)) {
            if ($this->setupOfficer === null) {
                $this->logger->warning("Setup handler require setup officer to detect older installations.");
            } elseif (method_exists($this->setupOfficer, 'hasOldInstallation')) {
                $this->logger->warning("'hasOldInstallation' method is deprecated. Implements 'retrieveOldInstallation' method instead.");

                if ($this->setupOfficer->hasOldInstallation()) {
                    $lastUpdate = $this->config['older'];
                }
            } else {
                $lastUpdate = $this->setupOfficer->retrieveOldInstallation();

                if ($lastUpdate) {
                    $this->logger->debug("'last_update' corrected by SetupOfficer : '$lastUpdate'.");
                }
            }
        }

        if ($lastUpdate !== $saveUpdate) {
            $this->setLastUpdate($lastUpdate);
        }

        return $lastUpdate;
    }
}
