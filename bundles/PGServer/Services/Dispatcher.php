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

namespace PGI\Module\PGServer\Services;

use PGI\Module\PGModule\Services\Broadcaster;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGServer\Components\Events\Action as ActionEventComponent;
use PGI\Module\PGServer\Foundations\AbstractController;
use PGI\Module\PGServer\Foundations\AbstractRequest;
use PGI\Module\PGServer\Foundations\AbstractResponse;
use PGI\Module\PGFramework\Components\Aggregator as AggregatorComponent;
use Exception;
use LogicException;

/**
 * Class Dispatcher
 * @package PGServer\Services
 */
class Dispatcher
{
    const DEFAULT_ACTION = 'process';

    /** @var Logger */
    private $logger;

    /** @var Broadcaster */
    private $broadcaster;

    /** @var AggregatorComponent */
    private $controllerAggregator;

    /** @var AggregatorComponent */
    private $actionAggregator;

    public function __construct(
        Logger $logger,
        Broadcaster $broadcaster,
        AggregatorComponent $controllerAggregator,
        AggregatorComponent $actionAggregator
    ) {
        $this->logger = $logger;
        $this->broadcaster = $broadcaster;
        $this->controllerAggregator = $controllerAggregator;
        $this->actionAggregator = $actionAggregator;
    }

    /**
     * @param AbstractRequest $request
     * @param string $localization
     * @return AbstractResponse
     * @throws Exception
     */
    public function dispatch(AbstractRequest $request, $localization)
    {
        if (!strpos($localization, '@')) {
            $action = $actionName = self::DEFAULT_ACTION;
            $controllerName = 'action.' . $localization;
            $controller = $this->actionAggregator->getService($localization);
        } else {
            list($actionName, $controllerName) = explode('@', $localization, 2);

            /** @var AbstractController $controller */
            $controller = $this->controllerAggregator->getService($controllerName);

            if (!empty($actionName)) {
                $action = $actionName . 'Action';
            } else {
                $action = self::DEFAULT_ACTION;
            }
        }

        $controller->setRequest($request);
        
        $class = get_class($controller);

        if (!method_exists($controller, $action)) {
            throw new Exception("Target controller '$class' has no action method '$action'.");
        }

        $event = new ActionEventComponent($request, $controller, $controllerName, $actionName);
        $this->broadcaster->fire($event);

        $this->logger->debug("Execute method '$action' on '$class'.");

        /** @var AbstractResponse $response */
        $response = call_user_func(array($controller, $action));

        $this->logger->debug("Response successfully built.");

        return $response;
    }
}
