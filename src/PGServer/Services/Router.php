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
 * @version   1.1.0
 */

class PGServerServicesRouter extends PGFrameworkFoundationsAbstractObject
{
    /** @var PGServerServicesHandlersAreaHandler */
    private $areaHandler;

    /** @var PGServerServicesHandlersRouteHandler */
    private $routeHandler;

    public function __construct(
        PGServerServicesHandlersAreaHandler $areaHandler,
        PGServerServicesHandlersRouteHandler $routeHandler
    ) {
        $this->areaHandler = $areaHandler;
        $this->routeHandler = $routeHandler;
    }

    /**
     * @param PGServerComponentsRequestsHTTPRequest $request
     * @param array $areas
     * @return string
     * @throws PGServerExceptionsHTTPNotFoundException
     * @throws Exception
     */
    public function getTarget(PGServerComponentsRequestsHTTPRequest $request, array $areas)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $action = $request->getAction();

        $this->verifyRoute($action);
        $this->verifyArea($action, $areas);

        $target = $this->routeHandler->getTarget($action);

        $logger->debug("Action '$action' successfully routed to '$target'.");

        return $target;
    }

    /**
     * @param string $action
     * @throws PGServerExceptionsHTTPNotFoundException
     */
    protected function verifyRoute($action)
    {
        if (!$this->routeHandler->has($action)) {
            throw new PGServerExceptionsHTTPNotFoundException("Action '$action' not found.");
        }
    }

    /**
     * @param string $action
     * @param array $areas
     * @throws PGServerExceptionsHTTPNotFoundException
     */
    protected function verifyArea($action, array $areas)
    {
        try {
            $area = $this->areaHandler->getRouteArea($action);
        } catch (Exception $exception) {
            throw new PGServerExceptionsHTTPNotFoundException(
                "Unable to retrieve route area : " . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        if (!in_array($area, $areas)) {
            throw new PGServerExceptionsHTTPNotFoundException("Action '$action' not found in any area of this server.");
        }
    }
}
