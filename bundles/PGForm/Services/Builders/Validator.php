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
 * @version   2.2.0
 *
 */

namespace PGI\Module\PGForm\Services\Builders;

use PGI\Module\PGForm\Interfaces\ValidatorInterface;
use PGI\Module\PGSystem\Services\Container;
use PGI\Module\PGSystem\Tools\Collection as CollectionTool;
use Exception;
use LogicException;

/**
 * Class Validator
 * @package PGForm\Services\Builders
 */
class Validator
{
    private $validatorNames = array();

    /** @var Container */
    private $container;

    public function __construct(Container $container)
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
     * @return ValidatorInterface
     * @throws LogicException
     * @throws Exception
     */
    protected function getValidator($name)
    {
        if (!array_key_exists($name, $this->validatorNames)) {
            throw new LogicException("Unknown validator name : '$name'.");
        }

        /** @var ValidatorInterface $validator */
        $validator = $this->container->get($this->validatorNames[$name]);

        if (! $validator instanceof ValidatorInterface) {
            throw new Exception("Validator '$name' must implements ValidatorInterface interface.");
        }

        return $validator;
    }

    public function build($name, $config)
    {
        /** @var ValidatorInterface $validator */
        $validator = $this->getValidator($name);

        $isSequentialArray = (is_array($config) && CollectionTool::isSequential($config));
        if (!is_array($config) || ($isSequentialArray)) {
            $config = array(
                'default' => $config
            );
        }

        $validator->setConfig($config);

        return $validator;
    }
}
