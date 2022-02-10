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
 * @version   2.5.2
 *
 */

namespace PGI\Module\PGSystem\Services\Autoloaders;

use Exception;

/**
 * Class CompiledAutoloader
 * @package PGSystem\Services\Autoloaders
 */
class CompiledAutoloader
{
    /** @var string */
    private $path;

    /** @var array */
    private $index;

    public function __construct($path, array $index)
    {
        $this->path = $path;
        $this->index = $index;
    }

    /**
     * @param $className
     * @return bool
     * @throws Exception
     */
    public function autoload($className)
    {
        if (isset($this->index[$className])) {
            $src = $this->path . DIRECTORY_SEPARATOR . $this->index[$className];

            if (!file_exists($src)) {
                $text = "File not found : '{$this->index[$className]}'. Unable to load '$className'.";
                throw new Exception($text);
            }

            require_once($src);

            return true;
        }

        return false;
    }
}
