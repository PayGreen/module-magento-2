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
 * Class PGShopServicesOrderStateMappingStrategiesSettings
 * @package PGShop\Services\OrderStateMappingStrategies
 */
class PGShopServicesOrderStateMappingStrategiesSettings extends PGShopFoundationsOrderStateMapperStrategy
{
    /** @var PGModuleServicesSettings */
    private $settings;

    /** @var PGShopServicesManagersOrderState */
    private $orderStateManager;

    public function __construct(PGModuleServicesSettings $settings)
    {
        $this->settings = $settings;
    }

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
     */
    public function getState(array $localState)
    {
        if (!array_key_exists('state', $localState)) {
            throw new Exception("localState must contains 'state' field.");
        }

        /** @var string $state */
        foreach (array_keys($this->getDefinitions()) as $state) {
            $id_searched_state = (int) $localState['state'];

            $id_finded_state = $this->getOrderStatePrimary($state);

            if ($id_searched_state === $id_finded_state) {
                return $state;
            }
        }

        return null;
    }

    /**
     * @param string $state
     * @return array
     * @throws PGSystemExceptionsConfiguration
     */
    public function getLocalState($state)
    {
        return array(
            'state' => $this->getOrderStatePrimary($state)
        );
    }

    /**
     * @param array $localState
     * @return bool
     * @throws Exception
     */
    public function isRecognizedLocalState(array $localState)
    {
        return ($this->getState($localState) !== null);
    }

    /**
     * @param string $state
     * @return int
     * @throws Exception
     * @throws PGSystemExceptionsConfiguration
     */
    protected function getOrderStatePrimary($state)
    {
        $definition = $this->getDefinition($state);

        if ($definition === null) {
            throw new Exception("OrderState definition not found : '$state'.");
        } elseif (!array_key_exists('name', $definition)) {
            $message = "Parameter 'name' not found in orderState definition '$state'.";
            throw new PGSystemExceptionsConfiguration($message);
        }

        $parameter = $definition['name'];

        $id_finded_state = (int) $this->settings->get($parameter);

        /** @var PGShopInterfacesEntitiesOrderState $orderState */
        $orderState = $this->orderStateManager->getByPrimary($id_finded_state);

        if ($orderState === null) {
            $orderState = $this->orderStateManager->create($state);

            if ($orderState === null) {
                throw new Exception("Can not create the orderState '$state'.");
            }
        }

        return $orderState->id();
    }
}
