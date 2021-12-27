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

namespace PGI\Module\PGModule\Services\Handlers;

use PGI\Module\PGFramework\Foundations\AbstractDiagnostic;
use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use PGI\Module\PGModule\Components\Events\Module as ModuleEventComponent;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use PGI\Module\PGSystem\Services\Container;
use Exception;

/**
 * Class DiagnosticHandler
 * @package PGModule\Services\Handlers
 */
class DiagnosticHandler extends AbstractObject
{
    /** @var Logger */
    private $logger;

    /** @var AggregatorComponent */
    private $diagnosticAggregator;

    private $bin;

    public function __construct(AggregatorComponent $diagnosticAggregator, Logger $logger)
    {
        $this->logger = $logger;
        $this->diagnosticAggregator = $diagnosticAggregator;
    }


    public function listen(ModuleEventComponent $event)
    {
        // Thrashing unused arguments
        $this->bin = $event;
        
        $this->logger->info("Running upgrade diagnostic.");

        $this->run();
    }

    /**
     * @return array
     */
    public function getDiagnosticNames()
    {
        return $this->diagnosticAggregator->getNames();
    }

    /**
     * @param bool $fix
     * @param null $name
     */
    public function run($fix = true, $name = null)
    {
        try {
            foreach ($this->diagnosticAggregator as $diagnosticName => $diagnostic) {
                if (($name === null) || ($diagnosticName === $name)) {
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
     * @return AbstractDiagnostic
     * @throws Exception
     */
    public function getDiagnostic($name)
    {
        /** @var AbstractDiagnostic $diagnostic */
        $diagnostic = $this->diagnosticAggregator->getService($name);

        if (!$diagnostic instanceof AbstractDiagnostic) {
            throw new Exception("'$name' is not a valid Diagnostic.");
        }

        return $diagnostic;
    }

    /**
     * @param AbstractDiagnostic $diagnostic
     * @param bool $fix
     */
    protected function diagnose(AbstractDiagnostic $diagnostic, $fix = true)
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
