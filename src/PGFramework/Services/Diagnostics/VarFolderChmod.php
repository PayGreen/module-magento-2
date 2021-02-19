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

class PGFrameworkServicesDiagnosticsVarFolderChmod extends PGFrameworkFoundationsAbstractDiagnostic
{
    /** @var PGFrameworkServicesPathfinder */
    private $pathfinder;

    public function __construct(PGFrameworkServicesPathfinder $pathfinder)
    {
        $this->pathfinder = $pathfinder;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isValid()
    {
        return (!$this->isFolderExists() || $this->isFolderChmodIsValid());
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function isFolderExists()
    {
        $path = $this->getVarPath();

        return (file_exists($path) && is_dir($path));
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function isFolderChmodIsValid()
    {
        $this->requirements();

        $chmod = PGFrameworkToolsFile::getChmod($this->getVarPath());

        return ($chmod === PGFrameworkBootstrap::VAR_FOLDER_CHMOD);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function resolve()
    {
        $this->requirements();

        return chmod(
            $this->getVarPath(),
            PGFrameworkBootstrap::VAR_FOLDER_CHMOD
        );
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getVarPath()
    {
        return $this->pathfinder->toAbsolutePath('var');
    }

    /**
     * @throws Exception
     */
    private function requirements()
    {
        $path = $this->getVarPath();

        if (!is_dir($path)) {
            throw new Exception("PayGreen var folder not found: $path");
        }
    }
}
