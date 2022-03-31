<?php
/**
 * 2014 - 2022 Watt Is It
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
 * @copyright 2014 - 2022 Watt Is It
 * @license   https://opensource.org/licenses/mit-license.php MIT License X11
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGModule\Services\Requirements;

use Exception;
use PGI\Module\PGFramework\Foundations\AbstractRequirement;
use PGI\Module\PGSystem\Exceptions\Configuration;
use PGI\Module\PGSystem\Services\Container;

/**
 * Class GenericBridgeRequirement
 * @package PGModule\Services\Requirements
 */
class GenericBridgeRequirement extends AbstractRequirement
{
    /** @var Container */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     * @throws Configuration
     * @throws Exception
     */
    public function isValid()
    {
        $serviceName = $this->getConfig('service');
        $methodName = $this->getConfig('method');
        $arguments = $this->getConfig('arguments');

        if (!$serviceName) {
            throw new Configuration("GenericBridgeRequirement require 'service' config parameter.");
        }

        if (!$methodName) {
            throw new Configuration("GenericBridgeRequirement require 'method' config parameter.");
        }

        if (!$arguments) {
            $arguments = array();
        }

        $service = $this->container->get($serviceName);

        return (bool) call_user_func_array(array($service, $methodName), $arguments);
    }
}
