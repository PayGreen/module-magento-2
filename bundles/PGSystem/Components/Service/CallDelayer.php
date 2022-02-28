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
 * @version   2.6.0
 *
 */

namespace PGI\Module\PGSystem\Components\Service;

use PGI\Module\PGSystem\Components\Parser as ParserComponent;
use PGI\Module\PGSystem\Services\Container;
use Exception;
use LogicException;

/**
 * Class CallDelayer
 * @package PGSystem\Components\Service
 */
class CallDelayer
{
    /** @var Container */
    private $container;

    /** @var ParserComponent */
    private $parser;

    private $delayedCalls = array();

    /**
     * CallDelayer constructor.
     * @param Container $container
     * @param ParserComponent $parser
     */
    public function __construct(Container $container, ParserComponent $parser)
    {
        $this->container = $container;
        $this->parser = $parser;
    }

    /**
     * @throws Exception
     */
    public function callDelayed()
    {
        while (!empty($this->delayedCalls)) {
            $callDefinition = array_shift($this->delayedCalls);

            $this->executeCall($callDefinition['subject'], $callDefinition['name'], $callDefinition['call']);
        }
    }

    /**
     * @param string $name
     * @param array $calls
     * @param object $subject
     */
    public function addCalls($name, $calls, $subject = null)
    {
        if (!is_array($calls)) {
            $message = "Target service definition has inconsistent call list : '$name'.";
            throw new LogicException($message);
        }

        foreach ($calls as $call) {
            $this->addCall($name, $call, $subject);
        }
    }

    /**
     * @param string $name
     * @param array $call
     * @param object $subject
     */
    public function addCall($name, $call, $subject = null)
    {
        if (!is_array($call)) {
            $message = "Target service definition has inconsistent call list : '$name'.";
            throw new LogicException($message);
        }

        $this->delayedCalls[] = array(
            'subject' => $subject,
            'name' => $name,
            'call' => $call
        );
    }

    /**
     * @param object $subject
     * @param string $name
     * @param array $delayedCall
     * @throws LogicException
     * @throws Exception
     */
    protected function executeCall($subject, $name, array $delayedCall)
    {
        if ($subject === null) {
            if (!$this->container->has($name)) {
                $message = "Unable to retrieve target service : '$name'.";
                throw new LogicException($message);
            }

            $service = $this->container->get($name);
        } else {
            $service = $subject;
        }

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
