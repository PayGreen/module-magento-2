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
 * @version   1.2.1
 *
 */

/**
 * Class PGFrameworkServicesHandlersBehaviorHandler
 * @package PGFramework\Services\Handlers
 */
class PGFrameworkServicesHandlersBehaviorHandler extends PGFrameworkFoundationsAbstractObject
{
    private $behaviors = array();

    public function __construct(array $behaviors)
    {
        $this->behaviors = $behaviors;
    }

    /**
     * @param $name
     * @param array $options
     * @return array|bool|float|int|string
     * @throws Exception
     */
    public function get($name, array $options = array())
    {
        if (!isset($this->behaviors[$name])) {
            throw new Exception("Unknown behavior name : '$name'.");
        } elseif (!isset($this->behaviors[$name]['type'])) {
            throw new Exception("Behavior '$name' has no declared type.");
        }

        $value = $this->getValue($name, $options);

        return $this->formatValue($name, $value);
    }

    protected function getValue($name, array $options = array())
    {
        $value = null;

        $behavior = $this->behaviors[$name];

        switch ($behavior['type']) {
            case 'fixed':
                if (!array_key_exists('value', $behavior)) {
                    throw new Exception("Behavior '$name' has no value defined.");
                }

                $value = $behavior['value'];

                break;

            case 'user':
                if (!array_key_exists('key', $behavior)) {
                    throw new Exception("Behavior '$name' has no key defined.");
                }

                /** @var PGFrameworkServicesSettings $settings */
                $settings = $this->getService('settings');

                $value = $settings->get($behavior['key']);

                break;

            case 'service':
                if (!array_key_exists('service', $behavior)) {
                    throw new Exception("Behavior '$name' has no service defined.");
                } elseif (!array_key_exists('method', $behavior)) {
                    throw new Exception("Behavior '$name' has no method defined.");
                }

                $service = $this->getService($behavior['service']);

                $value = call_user_func_array(array($service, $behavior['method']), $options);

                break;

            default:
                throw new Exception("Unrecognized behavior type : '{$behavior['type']}'.");
        }

        return $value;
    }

    protected function formatValue($name, $value)
    {
        $behavior = $this->behaviors[$name];

        if (isset($behavior['format'])) {
            switch ($behavior['format']) {
                case 'int':
                case 'integer':
                    $value = (int) $value;
                    break;

                case 'float':
                    $value = (float) $value;
                    break;

                case 'array':
                    $value = (array) $value;
                    break;

                case 'bool':
                case 'boolean':
                    $value = (bool) $value;
                    break;

                case 'string':
                    $value = (string) $value;
                    break;

                default:
                    throw new Exception("Unrecognized behavior format : '{$behavior['format']}'.");
            }
        }

        return $value;
    }
}
