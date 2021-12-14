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

namespace PGI\Module\PGFramework\Services\Handlers;

use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGSystem\Services\Container;
use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use Exception;

/**
 * Class HookHandler
 * @package PGFramework\Services\Handlers
 */
class HookHandler
{
    /** @var AggregatorComponent */
    private $hookAggregator;

    /** @var Logger */
    private $logger;

    private $hooks = array();

    public function __construct(AggregatorComponent $hookAggregator, Logger $logger)
    {
        $this->hookAggregator = $hookAggregator;
        $this->logger = $logger;
    }

    /**
     * @param string $hookName
     * @param string $methodName
     * @return callable
     */
    public function addHookName($hookName, $methodName)
    {
        $hookIdentifier = md5("$hookName-$methodName");

        $this->hooks[$hookIdentifier] = array(
            'hook' => $hookName,
            'method' => $methodName
        );

        return array($this, $hookIdentifier);
    }

    /**
     * @param string $hookIdentifier
     * @param array $arguments
     * @return mixed|void
     * @throws Exception
     */
    public function __call($hookIdentifier, array $arguments = array())
    {
        try {
            if (!isset($this->hooks[$hookIdentifier])) {
                throw new Exception("Unresolved hook or method not found : '$hookIdentifier'.");
            }

            $hookName = $this->hooks[$hookIdentifier]['hook'];
            $methodName = $this->hooks[$hookIdentifier]['method'];

            return $this->run($hookName, $methodName, $arguments);
        } catch (Exception $exception) {
            catchLowLevelException($exception, false);
        }
    }

    /**
     * @param string $hookName
     * @param string $methodName
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function run($hookName, $methodName, array $arguments = array())
    {
        $this->logger->debug("Running method '$methodName' on hook '$hookName'.");

        $service = $this->hookAggregator->getService($hookName);

        return call_user_func_array(array($service, $methodName), $arguments);
    }
}
