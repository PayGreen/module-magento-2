<?php
/**
 * 2014 - 2019 Watt Is It
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons BY-ND 4.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://creativecommons.org/licenses/by-nd/4.0/fr/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@paygreen.fr so we can send you a copy immediately.
 *
 * @author    PayGreen <contact@paygreen.fr>
 * @copyright 2014 - 2019 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 */

/**
 * Class PGFrameworkServicesPathfinder
 * @package PGFramework\Services
 */
class PGFrameworkServicesPathfinder
{
    private $bases = array();

    public function __construct(array $bases = array())
    {
        foreach ($bases as $name => $path) {
            $this->addBase($name, $path);
        }
    }

    public function addBase($name, $path)
    {
        $this->bases[$name] = $this->formatPath($path);
    }

    public function toAbsolutePath($base, $src = '')
    {
        if (!array_key_exists($base, $this->bases)) {
            throw new Exception("Unknown path origin : '$base'.");
        }

        return $this->bases[$base] . $this->formatPath($src);
    }

    public function formatPath($src)
    {
        $tokens = explode('/', $src);

        return implode(DIRECTORY_SEPARATOR, $tokens);
    }
}
