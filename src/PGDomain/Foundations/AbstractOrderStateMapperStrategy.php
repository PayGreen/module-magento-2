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
 * @version   1.2.2
 *
 */

abstract class PGDomainFoundationsAbstractOrderStateMapperStrategy extends PGFrameworkFoundationsAbstractObject implements PGDomainInterfacesOrderStateMapperStrategyInterface
{
    private $definitions = array();

    /**
     * @param array $definitions
     * @return void
     */
    public function setDefinitions(array $definitions)
    {
        $this->definitions = $definitions;
    }

    /**
     * @return array
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * @param string $state
     * @return array|null
     * @throws PGFrameworkExceptionsConfigurationException
     */
    public function getDefinition($state)
    {
        /**
         * @var string $state
         * @var array $definition
         */
        foreach ($this->getDefinitions() as $temporaryState => $definition) {
            if (!array_key_exists('name', $definition)) {
                $message = "Parameter 'name' not found in orderState definition : '$temporaryState'.";
                throw new PGFrameworkExceptionsConfigurationException($message);
            }

            if ($temporaryState === $state) {
                return $definition;
            }
        }

        return null;
    }
}
