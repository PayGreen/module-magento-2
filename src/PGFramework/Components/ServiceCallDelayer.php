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
 */

class PGFrameworkComponentsServiceCallDelayer
{
    /** @var PGFrameworkContainer */
    private $container;

    /** @var PGFrameworkComponentsParser */
    private $parser;

    private $delayedCalls = array();

    /**
     * PGFrameworkComponentsServiceCallDelayer constructor.
     * @param PGFrameworkContainer $container
     * @param PGFrameworkComponentsParser $parser
     */
    public function __construct(PGFrameworkContainer $container, PGFrameworkComponentsParser $parser)
    {
        $this->container = $container;
        $this->parser = $parser;
    }

    /**
     * @throws Exception
     */
    public function callDelayed()
    {
        while(!empty($this->delayedCalls)) {
            $callDefinition = array_pop($this->delayedCalls);

            $this->executeCall($callDefinition['name'], $callDefinition['call']);
        }
    }

    /**
     * @param string $name
     * @param array $calls
     */
    public function addCalls($name, $calls)
    {
            if (!is_array($calls)) {
                $message = "Target service definition has inconsistent call list : '$name'.";
                throw new LogicException($message);
            }

            foreach ($calls as $call) {
                $this->addCall($name, $call);
            }
    }

    /**
     * @param string $name
     * @param array $call
     */
    public function addCall($name, $call)
    {
        if (!is_array($call)) {
            $message = "Target service definition has inconsistent call list : '$name'.";
            throw new LogicException($message);
        }

        $this->delayedCalls[] = array(
            'name' => $name,
            'call' => $call
        );
    }

    /**
     * @param string $name
     * @param array $delayedCall
     * @throws LogicException
     * @throws Exception
     */
    protected function executeCall($name, array $delayedCall)
    {
        if (!$this->container->has($name)) {
            $message = "Unable to retrieve target service : '$name'.";
            throw new LogicException($message);
        }

        $service = $this->container->get($name);

        if (!array_key_exists('method', $delayedCall)) {
            $message = "Target service call has no method name : '$name'.";
            throw new LogicException($message);
        }

        $method = $delayedCall['method'];
        $arguments = array();

        if (array_key_exists('arguments', $delayedCall)) {
            if (!is_array($delayedCall['arguments'])) {
                $message = "Target service call has inconsistent argument list : '$name::$method'.";
                throw new LogicException($message);
            }

            foreach ($delayedCall['arguments'] as $argument) {
                $arguments[] = $this->parser->parseAll($argument);
            }
        }

        call_user_func_array(array($service, $method), $arguments);
    }
}
