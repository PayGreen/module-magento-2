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

class PGModuleServicesStrategiesOrderStateMagentoStrategy extends PGDomainFoundationsAbstractOrderStateMapperStrategy
{
    /**
     * @param array $localState
     * @return string|null
     * @throws Exception
     * @throws PGFrameworkExceptionsConfigurationException
     */
    public function getState(array $localState)
    {
        if (!array_key_exists('state', $localState)) {
            throw new Exception("localState must contains 'state' field.");
        } elseif (!array_key_exists('status', $localState)) {
            throw new Exception("localState must contains 'status' field.");
        }

        /**
         * @var string $state
         * @var array $definition
         */
        foreach ($this->getDefinitions() as $state => $definition) {
            if (!array_key_exists('state', $definition)) {
                $message = "Field 'state' not found in orderState definition : '$state'.";
                throw new PGFrameworkExceptionsConfigurationException($message);
            } elseif (!array_key_exists('status', $definition)) {
                $message = "Field 'status' not found in orderState definition : '$state'.";
                throw new PGFrameworkExceptionsConfigurationException($message);
            }

            if (($localState['state'] === $definition['state']) && ($localState['status'] === $definition['status'])) {
                return $state;
            }
        }

        return null;
    }

    /**
     * @param string $state
     * @return array
     * @throws Exception
     * @throws PGFrameworkExceptionsConfigurationException
     */
    public function getLocalState($state)
    {
        $definitions = $this->getDefinitions();

        if (!array_key_exists($state, $definitions)) {
            $message = "OrderState definition not found : '$state'.";
            throw new Exception($message);
        } elseif (!array_key_exists('state', $definitions[$state])) {
            $message = "Field 'state' not found in orderState definition : '$state'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!array_key_exists('status', $definitions[$state])) {
            $message = "Field 'status' not found in orderState definition : '$state'.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        }

        return [
            'state' => $definitions[$state]['state'],
            'status' => $definitions[$state]['status']
        ];
    }

    /**
     * @param array $localState
     * @return bool
     * @throws PGFrameworkExceptionsConfigurationException
     */
    public function isRecognizedLocalState(array $localState)
    {
        return ($this->getState($localState) !== null);
    }
}
