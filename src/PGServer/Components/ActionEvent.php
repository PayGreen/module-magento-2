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

/**
 * Class PGServerComponentsActionEvent
 * @package PGServer\Components
 */
class PGServerComponentsActionEvent extends PGFrameworkFoundationsAbstractEvent
{
    /** @var PGServerFoundationsAbstractRequest */
    private $request;

    /** @var PGServerFoundationsAbstractController */
    private $controller;

    /** @var string */
    private $controllerName;

    /** @var string */
    private $actionName;

    /** @var string */
    private $name;

    public function __construct(
        PGServerFoundationsAbstractRequest $request,
        PGServerFoundationsAbstractController $controller,
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
     * @return PGServerFoundationsAbstractRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return PGServerFoundationsAbstractController
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
