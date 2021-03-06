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
 * Class PGViewServicesHandlersViewHandler
 * @package PGView\Services\Handlers
 */
class PGViewServicesHandlersViewHandler
{
    const BASE_PATH = '/_resources/templates/';

    /** @var PGViewServicesBuildersViewBuilder */
    private $builderView;

    /** @var PGViewServicesHandlersSmartyHandler */
    private $smartyHandler;

    /** @var PGFrameworkServicesPathfinder */
    private $pathfinder;

    public function __construct(
        PGViewServicesBuildersViewBuilder $builderView,
        PGViewServicesHandlersSmartyHandler $smartyHandler,
        PGFrameworkServicesPathfinder $pathfinder
    ) {
        $this->builderView = $builderView;
        $this->smartyHandler = $smartyHandler;
        $this->pathfinder = $pathfinder;
    }

    /**
     * @param string $target
     * @return string
     * @throws Exception
     */
    public function resolveTemplatePath($target)
    {
        $source = self::BASE_PATH . $target . '.tpl';
        $src = $this->pathfinder->searchPath($source);

        if ($src === null) {
            throw new Exception("Template not found : '$source'.");
        }

        return $src;
    }

    /**
     * @param string $name
     * @return PGViewInterfacesViewInterface
     * @throws Exception
     */
    public function buildView($name)
    {
        return $this->builderView
            ->build($name)
        ;
    }

    /**
     * @param string $name
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function renderView($name, array $data = array())
    {
        return $this->buildView($name)
            ->setData($data)
            ->render()
        ;
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function renderTemplate($template, array $data = array())
    {
        return $this->smartyHandler->compileTemplate(
            $this->resolveTemplatePath($template),
            $data
        );
    }
}
