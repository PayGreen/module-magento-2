<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.0.0
 */

class PGServerComponentsStage extends PGFrameworkFoundationsAbstractObject
{
    private $config;

    /** @var PGServerComponentsTrigger|null */
    private $trigger;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    public $do;

    public $with;

    public function __construct(array $config, PGServerComponentsTrigger $trigger = null)
    {
        $this->config = $config;
        $this->trigger = $trigger;

        $this->do = strtoupper($this->config['do']);
        $this->with = isset($this->config['with']) ? $this->config['with'] : null;
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
     * @return PGFrameworkServicesLogger
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param PGFrameworkServicesLogger $logger
     */
    public function setLogger(PGFrameworkServicesLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param PGServerFoundationsAbstractResponse $response
     * @return bool
     */
    public function isTriggered(PGServerFoundationsAbstractResponse $response)
    {
        return ($this->trigger === null) ? true : $this->trigger->isTriggered($response);
    }

    /**
     * @param PGServerFoundationsAbstractResponse $response
     * @return mixed
     * @throws Exception
     */
    protected function callService(PGServerFoundationsAbstractResponse $response)
    {
        if ($this->with === null) {
            throw new Exception("Server Stage must specify service to perform action.");
        }

        $service = $this->getService($this->with);

        if (!method_exists($service, 'process')) {
            throw new Exception("@{$this->with} is not a valid renderer. Target service must implements 'process' method.");
        }

        $arguments = array($response, $this->getConfig('config'));

        return call_user_func_array(array($service, 'process'), $arguments);
    }

    /**
     * @param PGServerFoundationsAbstractResponse $response
     * @return PGServerFoundationsAbstractResponse|null
     * @throws Exception
     */
    public function execute(PGServerFoundationsAbstractResponse $response)
    {
        if ($this->with !== null) {
            $this->getLogger()->debug("Process Response with '@{$this->with}'.");

            return $this->callService($response);
        } else {
            return $response;
        }
    }
}
