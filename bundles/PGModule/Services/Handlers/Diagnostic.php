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
 * @version   2.1.0
 *
 */

/**
 * Class PGModuleServicesHandlersDiagnostic
 * @package PGModule\Services\Handlers
 */
class PGModuleServicesHandlersDiagnostic extends PGSystemFoundationsObject
{
    private $diagnosticNames = array();

    /** @var PGModuleServicesLogger */
    private $logger;

    /** @var PGSystemServicesContainer */
    private $container;

    private $bin;

    public function __construct(PGSystemServicesContainer $container, PGModuleServicesLogger $logger)
    {
        $this->logger = $logger;
        $this->container = $container;
    }


    public function listen(PGModuleComponentsEventsModule $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;
        
        $this->logger->info("Running upgrade diagnostic.");

        $this->run();
    }

    public function addDiagnosticName($serviceName)
    {
        $this->diagnosticNames[] = $serviceName;
    }

    /**
     * @return array
     */
    public function getDiagnosticNames()
    {
        return $this->diagnosticNames;
    }

    /**
     * @param bool $fix
     * @param null $name
     */
    public function run($fix = true, $name = null)
    {
        try {
            foreach ($this->diagnosticNames as $diagnosticName) {
                if (($name === null) || ($diagnosticName === $name)) {
                    /** @var PGFrameworkFoundationsDiagnostic $diagnostic */
                    $diagnostic = $this->container->get($diagnosticName);

                    if (!$diagnostic instanceof PGFrameworkFoundationsDiagnostic) {
                        throw new Exception("'$diagnosticName' is not a valid Diagnostic.");
                    }

                    $this->diagnose($diagnostic, $fix);
                }
            }
        } catch (Exception $exception) {
            $this->logger->critical(
                "Critical error during diagnostic process : " . $exception->getMessage(),
                $exception
            );
        }
    }

    /**
     * @param string $name
     * @return PGFrameworkFoundationsDiagnostic
     * @throws Exception
     */
    public function getDiagnostic($name)
    {
        /** @var PGFrameworkFoundationsDiagnostic $diagnostic */
        $diagnostic = $this->container->get($name);

        if (!$diagnostic instanceof PGFrameworkFoundationsDiagnostic) {
            throw new Exception("'$name' is not a valid Diagnostic.");
        }

        return $diagnostic;
    }

    /**
     * @param PGFrameworkFoundationsDiagnostic $diagnostic
     * @param bool $fix
     */
    protected function diagnose(PGFrameworkFoundationsDiagnostic $diagnostic, $fix = true)
    {
        $name = get_class($diagnostic);

        if ($diagnostic->isValid()) {
            $this->logger->notice("Diagnostic '$name' is valid.");
        } else {
            $this->logger->error("Diagnostic '$name' is not valid.");

            if ($fix) {
                if ($diagnostic->resolve()) {
                    $this->logger->info("Correction of diagnostic '$name' is successfully executed.");
                } else {
                    $this->logger->error("Unable to resolve diagnostic '$name'.");
                }
            }
        }
    }
}
