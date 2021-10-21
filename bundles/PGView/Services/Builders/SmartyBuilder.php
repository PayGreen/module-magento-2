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
 * @version   2.4.0
 *
 */

namespace PGI\Module\PGView\Services\Builders;

use PGI\Module\PGSystem\Components\Bag as BagComponent;
use PGI\Module\PGSystem\Services\Pathfinder;
use PGI\Module\PGView\Interfaces\Builders\SmartyBuilderInterface;
use Exception;
use Smarty;

/**
 * Class SmartyBuilder
 * @package PGView\Services\Builders
 */
class SmartyBuilder implements SmartyBuilderInterface
{
    /** @var Pathfinder */
    protected $pathfinder;

    protected $config;

    /**
     * SmartyBuilder constructor.
     * @param Pathfinder $pathfinder
     * @param array $config
     */
    public function __construct(Pathfinder $pathfinder, array $config)
    {
        $this->pathfinder = $pathfinder;
        $this->config = new BagComponent($config);
    }

    /**
     * @return Smarty
     * @throws Exception
     */
    public function build()
    {
        if (!class_exists('Smarty')) {
            if (!$this->config['path']) {
                throw new Exception(
                    "Smarty path not found. You must indicate path to Smarty in the 'smarty.builder.path' parameter."
                );
            }

            require $this->pathfinder->toAbsolutePath($this->config['path']);
        }

        $smarty = new Smarty();
        $folders = array();

        foreach ($this->config['template_folders'] as $folder) {
            $path = $this->pathfinder->toAbsolutePath($folder);

            if (is_dir($path)) {
                $folders[] = $path;
            }
        }

        $smarty->setTemplateDir($folders);

        return $smarty;
    }
}
