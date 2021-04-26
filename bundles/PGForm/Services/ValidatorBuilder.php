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
 * @version   2.0.0
 *
 */

/**
 * Class PGFormServicesValidatorBuilder
 * @package PGForm\Services
 */
class PGFormServicesValidatorBuilder
{
    private $validatorNames = array();

    /** @var PGSystemServicesContainer */
    private $container;

    public function __construct(PGSystemServicesContainer $container)
    {
        $this->container = $container;
    }

    public function addValidatorServiceName($serviceName, $validatorName = null)
    {
        if ($validatorName === null) {
            if (preg_match("/^validator\.(?P<name>.+)/", $serviceName, $result)) {
                $validatorName = $result['name'];
            } else {
                throw new Exception(
                    "Unable to automatically determine the validator name with the service name : '$serviceName'."
                );
            }
        }

        $this->validatorNames[$validatorName] = $serviceName;
    }

    /**
     * @param string $name
     * @return PGFormInterfacesValidatorInterface
     * @throws LogicException
     * @throws Exception
     */
    protected function getValidator($name)
    {
        if (!array_key_exists($name, $this->validatorNames)) {
            throw new LogicException("Unknown validator name : '$name'.");
        }

        /** @var PGFormInterfacesValidatorInterface $validator */
        $validator = $this->container->get($this->validatorNames[$name]);

        if (! $validator instanceof PGFormInterfacesValidatorInterface) {
            throw new Exception("Validator '$name' must implements PGFormInterfacesValidatorInterface interface.");
        }

        return $validator;
    }

    public function build($name, $config)
    {
        /** @var PGFormInterfacesValidatorInterface $validator */
        $validator = $this->getValidator($name);

        $isSequentialArray = (is_array($config) && PGSystemToolsArray::isSequential($config));
        if (!is_array($config) || ($isSequentialArray)) {
            $config = array(
                'default' => $config
            );
        }

        $validator->setConfig($config);

        return $validator;
    }
}
