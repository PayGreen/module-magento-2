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
 * @version   2.0.0
 *
 */

/**
 * Class PGViewServicesView
 * @package PGView\Services
 */
class PGViewServicesView implements PGViewInterfacesViewInterface
{
    /** @var PGViewServicesHandlersViewHandler */
    private $viewHandler;

    private $data = array();

    private $template;

    /**
     * @param PGViewServicesHandlersViewHandler $viewHandler
     */
    public function setViewHandler(PGViewServicesHandlersViewHandler $viewHandler)
    {
        $this->viewHandler = $viewHandler;
    }

    /**
     * @return PGViewServicesHandlersViewHandler $viewHandler
     */
    public function getViewHandler()
    {
        return $this->viewHandler;
    }

    /**
     * @inheritDoc
     */
    public function setData(array $data)
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getTemplate()
    {
        return $this->template;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function render()
    {
        return $this->viewHandler->renderTemplate(
            $this->getTemplate(),
            $this->getData()
        );
    }

    protected function get($key)
    {
        return $this->has($key) ? $this->data[$key] : null;
    }

    protected function set($key, $val)
    {
        $this->data[$key] = $val;

        return $this;
    }

    protected function rem($key)
    {
        if ($this->has($key)) {
            unset($this->data[$key]);
        }

        return $this;
    }

    protected function has($key)
    {
        return array_key_exists($key, $this->data);
    }
}
