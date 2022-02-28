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

namespace PGI\Module\BOModule\Services\Deflectors;

use PGI\Module\PGServer\Components\Responses\Redirection as RedirectionResponseComponent;
use PGI\Module\PGServer\Foundations\AbstractDeflector;
use PGI\Module\PGServer\Foundations\AbstractRequest;
use PGI\Module\PGServer\Services\Handlers\RouteHandler;
use Exception;

/**
 * Class ShopContext
 * @package BOModule\Services\Deflectors
 */
class ShopContext extends AbstractDeflector
{
    /** @var RouteHandler */
    private $routeHandler;

    public function __construct(RouteHandler $routeHandler)
    {
        $this->routeHandler = $routeHandler;
    }

    /**
     * @param AbstractRequest $request
     * @return bool
     * @throws Exception
     */
    public function isMatching(AbstractRequest $request)
    {
        $result = false;
        $action = $request->getAction();

        if (!empty($action)) {
            $result = !$this->routeHandler->isRequirementFulfilled($action, 'shop_context');
        }

        return $result;
    }

    /**
     * @return RedirectionResponseComponent
     * @throws Exception
     */
    protected function buildResponse()
    {
        return $this->redirect($this->getLinkHandler()->buildBackOfficeUrl());
    }
}
