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
 * @version   2.1.0
 *
 */

/**
 * Class PGViewServicesHandlersSmartyHandler
 * @package PGView\Services\Handlers
 */
class PGViewServicesHandlersSmartyHandler extends PGSystemFoundationsObject
{
    /** @var Smarty */
    private $smarty = null;

    /** @var PGSystemServicesPathfinder */
    private $pathfinder;

    /** @var PGModuleServicesLogger */
    private $logger;

    /** @var PGSystemComponentsBag */
    private $config;

    /**
     * PGViewServicesHandlersSmartyHandler constructor.
     * @param PGViewInterfacesSmartyBuilderInterface $smartyBuilder
     * @param PGSystemServicesPathfinder $pathfinder
     * @param PGModuleServicesLogger $logger
     * @throws Exception
     */
    public function __construct(
        PGViewInterfacesSmartyBuilderInterface $smartyBuilder,
        PGSystemServicesPathfinder $pathfinder,
        PGModuleServicesLogger $logger,
        array $config
    ) {
        $this->pathfinder = $pathfinder;
        $this->logger = $logger;
        $this->config = new PGSystemComponentsBag($config);

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

            if (isset($this->config['null_stream'])) {
                if (in_array('pgnull', stream_get_wrappers())) {
                    stream_wrapper_unregister('pgnull');
                }
    
                stream_wrapper_register('pgnull', $this->config['null_stream']);
                
                $smarty->setCompileDir('pgnull://');
                $smarty->setCacheDir('pgnull://');
            } else {
                throw new Exception('No valid cache repository');
            }
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
            $recognized_plugins = "'function', 'block', 'compiler' and 'modifier'";
            throw new Exception(
                "Smarty handler only recognise $recognized_plugins Smarty plugin. '$type' plugin is not allowed.'"
            );
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
