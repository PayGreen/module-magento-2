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
 * @version   1.2.5
 *
 */

class APPbackofficeServicesDeflectorsShopContextDeflector extends PGServerFoundationsAbstractDeflector
{
    /** @var PGServerServicesHandlersRouteHandler */
    private $routeHandler;

    public function __construct(PGServerServicesHandlersRouteHandler $routeHandler)
    {
        $this->routeHandler = $routeHandler;
    }

    /**
     * @param PGServerFoundationsAbstractRequest $request
     * @return bool
     * @throws Exception
     */
    public function isMatching(PGServerFoundationsAbstractRequest $request)
    {
        $result = false;
        $action = $request->getAction();

        if (!empty($action)) {
            $result = !$this->routeHandler->isRequirementFulfilled($action, 'shop_context');
        }

        return $result;
    }

    /**
     * @return PGServerComponentsResponsesRedirectionResponse
     * @throws Exception
     */
    protected function buildResponse()
    {
        return $this->redirect($this->getLinker()->buildBackOfficeUrl());
    }
}
