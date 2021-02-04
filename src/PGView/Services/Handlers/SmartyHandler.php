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
 * @version   1.2.2
 *
 */

/**
 * Class PGViewServicesHandlersSmartyHandler
 * @package PGView\Services\Handlers
 */
class PGViewServicesHandlersSmartyHandler extends PGFrameworkFoundationsAbstractObject
{
    /** @var Smarty */
    private $smarty = null;

    /** @var PGFrameworkServicesPathfinder */
    private $pathfinder;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    /**
     * PGViewServicesHandlersSmartyHandler constructor.
     * @param PGViewInterfacesSmartyBuilderInterface $smartyBuilder
     * @param PGFrameworkServicesPathfinder $pathfinder
     * @param PGFrameworkServicesLogger $logger
     * @throws Exception
     */
    public function __construct(
        PGViewInterfacesSmartyBuilderInterface $smartyBuilder,
        PGFrameworkServicesPathfinder $pathfinder,
        PGFrameworkServicesLogger $logger
    ) {
        $this->pathfinder = $pathfinder;
        $this->logger = $logger;

        $this->smarty = $smartyBuilder->build();

        $this->configureSmarty($this->smarty);
    }

    /**
     * @param Smarty $smarty
     * @throws Exception
     */
    protected function configureSmarty(Smarty $smarty)
    {
        $varFolder = $this->pathfinder->getBasePath('cache');

        $paths = $this->pathfinder->reviewVendorPaths('/_resources/templates');
        $smarty->setTemplateDir(array_reverse($paths));

        if (is_dir($varFolder) && is_writable($varFolder)) {
            $path = $this->pathfinder->toAbsolutePath('cache:/smarty/compiled');
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            $smarty->setCompileDir($path);

            $path = $this->pathfinder->toAbsolutePath('cache:/smarty/cache');
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            $smarty->setCacheDir($path);

            if (PAYGREEN_ENV !== 'DEV') {
                $smarty->setCompileCheck(false);
            }
        } else {
            $this->logger->warning("Smarty configured without cache folders.");

            if (in_array('pgnull', stream_get_wrappers())) {
                stream_wrapper_unregister('pgnull');
            }

            stream_wrapper_register('pgnull', 'PGViewComponentsNullStream');

            $smarty->setCompileDir('pgnull://');
            $smarty->setCacheDir('pgnull://');
        }

        $smarty->setCaching(Smarty::CACHING_OFF);
    }

    /**
     * @param object $service
     * @param string $modifierName
     * @param string $method
     * @param string $type
     * @throws SmartyException
     * @throws Exception
     */
    public function installPlugin($service, $modifierName, $method, $type = 'modifier')
    {
        if (!in_array($type, array('function', 'block', 'compiler', 'modifier'))) {
            throw new Exception("Smarty handler only recognise 'function', 'block', 'compiler' and 'modifier' Smarty plugin. '$type' plugin is not allowed.'");
        }

        $serviceName = get_class($service);

        $this->logger->debug("Register Smarty plugin '$modifierName' with callback '$serviceName::$method'.");

        $this->smarty->registerPlugin($type, $modifierName, array($service, $method));
    }

    /**
     * @return Smarty
     */
    public function getSmarty()
    {
        return $this->smarty;
    }

    /**
     * @param $src
     * @param array $data
     * @return string
     * @throws SmartyException
     */
    public function compileTemplate($src, array $data = array())
    {
        $this->smarty->clearAllAssign();

        $this->smarty->assign($data);

        $this->logger->debug("Fetching target template : '$src'.");

        return $this->smarty->fetch($src);
    }
}
