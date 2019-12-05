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
 * @version   0.3.4
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
