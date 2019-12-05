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
 * @version   0.3.5
 */

/**
 * Class PGFrameworkServicesSettings
 * @package PGFramework\Services
 */
class PGFrameworkServicesSettings extends PGFrameworkFoundationsAbstractObject
{
    protected $definitions = array(
        'shipping_deactivated_payment_modes' => array(
            'type' => 'array',
            'default' => array()
        )
    );

    /** @var PGFrameworkInterfacesOfficersSettingsOfficerInterface */
    private $settingOfficer;

    public function __construct(PGFrameworkInterfacesOfficersSettingsOfficerInterface $settingOfficer)
    {
        $this->settingOfficer = $settingOfficer;
    }

    /**
     * @return PGFrameworkInterfacesOfficersSettingsOfficerInterface
     */
    public function getSettingOfficer()
    {
        return $this->settingOfficer;
    }

    public function get($name)
    {
        $value = $this->settingOfficer->getOption($name, $this->getDefault($name));

        $value = $this->unformat($name, $value);

        return $value;
    }

    public function set($name, $value)
    {
        $value = $this->format($name, $value);

        $this->settingOfficer->setOption($name, $value);
    }

    public function reset($name)
    {
        $this->set($name, $this->getDefault($name));
    }

    protected function getDefinition($name)
    {
        return array_key_exists($name, $this->definitions) ? $this->definitions[$name] : array();
    }

    protected function getDefault($name)
    {
        $definition = $this->getDefinition($name);

        return array_key_exists('default', $definition) ? $definition['default'] : null;
    }

    protected function format($name, $value)
    {
        $definitions = $this->getDefinition($name);

        if (array_key_exists('type', $definitions)) {
            switch ($definitions['type']) {
                case 'array':
                    if (!is_array($value)) {
                        throw new Exception("'$name' parameters should be an array.");
                    }

                    $value = serialize($value);

                    break;
                default:
                    $value = trim($value);
            }
        }

        return $value;
    }

    protected function unformat($name, $value)
    {
        $definitions = $this->getDefinition($name);

        if (array_key_exists('type', $definitions)) {
            switch ($definitions['type']) {
                case 'array':
                    if (!is_array($value)) {
                        $value = unserialize($value);
                    }

                    break;
                default:
                    $value = trim($value);
            }
        }

        return $value;
    }
}
