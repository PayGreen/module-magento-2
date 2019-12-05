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
 * @version   0.3.4
 */

/**
 * Class PGFrameworkComponentsParameters
 * @package PGFramework\Components
 */
class PGFrameworkComponentsParameters implements arrayaccess
{
    /** @var PGFrameworkComponentsBag */
    private $bag;

    public function __construct()
    {
        $this->bag = new PGFrameworkComponentsBag();
    }

    /**
     * @return PGFrameworkComponentsBag
     */
    public function getBag()
    {
        return $this->bag;
    }

    /**
     * @param string $filename
     * @return $this
     * @throws Exception
     */
    public function addParametersFile($filename)
    {
        $data = json_decode(file_get_contents($filename), true);

        if (!$data) {
            throw new Exception("Unable to load parameters file : '$filename'.");
        }

        $this->bag->merge($data);

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     * @throws Exception
     */
    public function addParametersFolder($path)
    {
        if (!is_dir($path)) {
            throw new Exception("Parameters folder not found : '$path'.");
        }

        foreach (glob($path . DIRECTORY_SEPARATOR . '*.json') as $filename) {
            $this->addParametersFile($filename);
        }

        return $this;
    }

    // ###################################################################
    // ###       sous-fonctions d'accès par tableau
    // ###################################################################

    public function offsetSet($var, $value)
    {
        throw new Exception("Can not manually add a parameter.");
    }
    public function offsetExists($var)
    {
        return isset($this->bag[$var]);
    }
    public function offsetUnset($var)
    {
        throw new Exception("Can not manually delete a parameter.");
    }
    public function offsetGet($var)
    {
        return $this->bag[$var];
    }
}
