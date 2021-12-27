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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGServer\Components\Events;

use PGI\Module\PGModule\Foundations\AbstractEvent;
use PGI\Module\PGServer\Foundations\AbstractController;
use PGI\Module\PGServer\Foundations\AbstractRequest;

/**
 * Class Action
 * @package PGServer\Components\Events
 */
class Action extends AbstractEvent
{
    /** @var AbstractRequest */
    private $request;

    /** @var AbstractController */
    private $controller;

    /** @var string */
    private $controllerName;

    /** @var string */
    private $actionName;

    /** @var string */
    private $name;

    public function __construct(
        AbstractRequest $request,
        AbstractController $controller,
        $controllerName,
        $actionName
    ) {
        $this->name = 'ACTION.' . strtoupper($request->getAction());
        $this->request = $request;
        $this->controller = $controller;
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return AbstractRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return AbstractController
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }
}
