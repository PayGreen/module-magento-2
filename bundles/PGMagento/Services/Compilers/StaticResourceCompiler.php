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
 * @version   2.6.1
 *
 */

namespace PGI\Module\PGMagento\Services\Compilers;

use PGI\Module\PGModule\Services\Handlers\StaticFileHandler;
use PGI\Module\PGServer\Components\ResourceBag as ResourceBagComponent;
use PGI\Module\PGServer\Foundations\AbstractResourceBasic;

/**
 * Class StaticResourceCompiler
 * @package PGMagento\Services\Compilers
 */
class StaticResourceCompiler
{
    /** @var StaticFileHandler */
    private $staticFileHandler;

    public function __construct(StaticFileHandler $staticFileHandler)
    {
        $this->staticFileHandler = $staticFileHandler;
    }

    public function compileResources(ResourceBagComponent $resources)
    {
        $output = '';

        /** @var AbstractResourceBasic $resource */
        foreach ($resources->get() as $resource)
        {
            switch($resource::NAME) {
                case 'JS-FILE':
                    $url = $this->staticFileHandler->getUrl($resource->getPath());
                    $output .= '<script type="text/javascript" src="' . $url . '"></script>';
                    break;
                case 'CSS-FILE':
                    $url = $this->staticFileHandler->getUrl($resource->getPath());
                    $output .= '<link rel="stylesheet" type="text/css" media="all" href="' . $url . '" />';
                    break;
                case 'JS-DATA':
                    $code = '<script type="text/javascript">' . PHP_EOL;

                    foreach ($resource->getData() as $key => $val) {
                        $json = json_encode($val);
                        $code .= "var $key = $json" . PHP_EOL;
                    }

                    $code .= '</script>' . PHP_EOL;

                    $output .= $code;

                    break;
            }
        }

        return $output;
    }
}
