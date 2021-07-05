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

class PGMagentoPaymentServicesStrategiesOrderStateMagentoStrategy extends PGShopFoundationsOrderStateMapperStrategy
{
    /** @var PGShopServicesManagersOrderState */
    private $orderStateManager;

    /**
     * @param PGShopServicesManagersOrderState $orderStateManager
     */
    public function setOrderStateManager(PGShopServicesManagersOrderState $orderStateManager)
    {
        $this->orderStateManager = $orderStateManager;
    }

    /**
     * @param array $localState
     * @return string|null
     * @throws Exception
     * @throws PGSystemExceptionsConfiguration
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
                throw new PGSystemExceptionsConfiguration($message);
            } elseif (!array_key_exists('status', $definition)) {
                $message = "Field 'status' not found in orderState definition : '$state'.";
                throw new PGSystemExceptionsConfiguration($message);
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
     * @throws PGSystemExceptionsConfiguration
     */
    public function getLocalState($state)
    {
        $definitions = $this->getDefinitions();

        if (!array_key_exists($state, $definitions)) {
            $message = "OrderState definition not found : '$state'.";
            throw new Exception($message);
        } elseif (!array_key_exists('state', $definitions[$state])) {
            $message = "Field 'state' not found in orderState definition : '$state'.";
            throw new PGSystemExceptionsConfiguration($message);
        } elseif (!array_key_exists('status', $definitions[$state])) {
            $message = "Field 'status' not found in orderState definition : '$state'.";
            throw new PGSystemExceptionsConfiguration($message);
        }

        $this->verifyOrderStateExistence($state, $definitions[$state]['status']);

        return [
            'state' => $definitions[$state]['state'],
            'status' => $definitions[$state]['status']
        ];
    }

    /**
     * @param array $localState
     * @return bool
     * @throws PGSystemExceptionsConfiguration
     */
    public function isRecognizedLocalState(array $localState)
    {
        return ($this->getState($localState) !== null);
    }

    /**
     * @param $state
     * @param $status
     * @throws PGSystemExceptionsConfiguration
     */
    protected function verifyOrderStateExistence($state, $status)
    {
        /** @var PGShopInterfacesEntitiesOrderState $orderState */
        $orderState = $this->orderStateManager->getByPrimary($status);

        if ($orderState === null) {
            $this->getService('logger')->notice("Creating order state : '$state'.");
            $orderState = $this->orderStateManager->create($state);

            if ($orderState === null) {
                throw new Exception("Can not create the orderState '$state'.");
            }
        }
    }
}
