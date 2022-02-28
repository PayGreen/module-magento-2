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

namespace PGI\Module\PGServer\Services;

use PGI\Module\PGLog\Interfaces\LoggerInterface;
use PGI\Module\PGServer\Components\Requests\HTTP as HTTPRequestComponent;
use PGI\Module\PGServer\Exceptions\HTTPNotFound as HTTPNotFoundException;
use PGI\Module\PGServer\Exceptions\HTTPUnauthorized as HTTPUnauthorizedException;
use PGI\Module\PGServer\Services\Handlers\AreaHandler;
use PGI\Module\PGServer\Services\Handlers\RouteHandler;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;

/**
 * Class Router
 * @package PGServer\Services
 */
class Router extends AbstractObject
{
    /** @var AreaHandler */
    private $areaHandler;

    /** @var RouteHandler */
    private $routeHandler;

    public function __construct(
        AreaHandler $areaHandler,
        RouteHandler $routeHandler
    ) {
        $this->areaHandler = $areaHandler;
        $this->routeHandler = $routeHandler;
    }

    /**
     * @param HTTPRequestComponent $request
     * @param array $areas
     * @return string
     * @throws HTTPNotFoundException
     * @throws Exception
     */
    public function getTarget(HTTPRequestComponent $request, array $areas)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->getService('logger');

        $action = $request->getAction();

        $this->verifyRoute($action);
        $this->verifyArea($action, $areas);
        $this->verifyRequirements($action);

        $target = $this->routeHandler->getTarget($action);

        $logger->debug("Action '$action' successfully routed to '$target'.");

        return $target;
    }

    /**
     * @param string $action
     * @throws HTTPNotFoundException
     */
    protected function verifyRoute($action)
    {
        if (!$this->routeHandler->has($action)) {
            throw new HTTPNotFoundException("Action '$action' not found.");
        }
    }

    /**
     * @param string $action
     * @param array $areas
     * @throws HTTPNotFoundException
     */
    protected function verifyArea($action, array $areas)
    {
        try {
            $area = $this->areaHandler->getRouteArea($action);
        } catch (Exception $exception) {
            throw new HTTPNotFoundException(
                "Unable to retrieve route area : " . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        if (!in_array($area, $areas)) {
            throw new HTTPNotFoundException("Action '$action' not found in any area of this server.");
        }
    }

    /**
     * @param string $action
     * @throws HTTPUnauthorizedException
     */
    protected function verifyRequirements($action)
    {
        if (!$this->routeHandler->areRequirementsFulfilled($action)) {
            throw new HTTPUnauthorizedException("Route '$action' requirements are not fulfilled.");
        }
    }
}
