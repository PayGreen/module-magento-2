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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGServer\Foundations;

use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGServer\Components\Trigger as TriggerComponent;
use PGI\Module\PGServer\Foundations\AbstractResponse;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class AbstractStage
 * @package PGServer\Foundations
 */
abstract class AbstractStage extends AbstractObject
{
    private $config;

    /** @var TriggerComponent|null */
    private $trigger;

    /** @var Logger */
    private $logger;

    public function __construct(array $config, TriggerComponent $trigger = null)
    {
        $this->config = $config;
        $this->trigger = $trigger;
    }

    /**
     * @param string|null $key
     * @return array|mixed|null
     */
    protected function getConfig($key = null)
    {
        if ($key === null) {
            return $this->config;
        } elseif (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        } else {
            return null;
        }
    }

    /**
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param AbstractResponse $response
     * @return bool
     */
    public function isTriggered(AbstractResponse $response)
    {
        return ($this->trigger === null) ? true : $this->trigger->isTriggered($response);
    }

    /**
     * @param AbstractResponse $response
     * @return mixed
     * @throws Exception
     */
    protected function callService(AbstractResponse $response)
    {
        $stageType = static::STAGE_TYPE;
        $stageMethod = static::STAGE_METHOD;

        $serviceName = $this->getConfig('with');

        if ($serviceName === null) {
            throw new Exception("Server Stage must specify service to perform action.");
        }

        $service = $this->getService($serviceName);

        if (!method_exists($service, $stageMethod)) {
            throw new Exception(
                "@$serviceName is not a valid $stageType. Target service must implements '$stageMethod' method."
            );
        }

        $this->getLogger()->debug(ucfirst($stageMethod) . " Response with '@$serviceName'.");

        $arguments = array($response, $this->getConfig('config'));

        return call_user_func_array(array($service, $stageMethod), $arguments);
    }

    /**
     * @param AbstractResponse $response
     * @return AbstractResponse|null
     */
    abstract public function execute(AbstractResponse $response);
}
