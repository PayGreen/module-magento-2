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
 * Class PGFrameworkComponentsParameters
 * @package PGFramework\Components
 */
class PGFrameworkComponentsParameters implements arrayaccess
{
    /** @var PGFrameworkComponentsBag */
    private $bag;

    /** @var PGFrameworkComponentsParser */
    private $parser;

    /** @var array */
    private $files = array();

    public function __construct()
    {
        $this->parser = new PGFrameworkComponentsParser(array());

        $this->buildParametersBag();
    }

    private function buildParametersBag()
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
     * @throws Exception
     */
    public function reset()
    {
        $this->buildParametersBag();

        $filenames = $this->files;

        $this->files = array();

        foreach ($filenames as $filename) {
            $this->addParametersFile($filename);
        }
    }

    /**
     * @param string $filename
     * @return $this
     * @throws Exception
     */
    public function addParametersFile($filename)
    {
        $data = json_decode(file_get_contents($filename), true);

        $this->files[] = $filename;

        if (!$data) {
            throw new Exception("Unable to load parameters file : '$filename'.");
        }

        $data = $this->parseConstants($data);

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

    /**
     * @param array $data
     * @return array
     * @throws PGFrameworkExceptionsParserConstantException
     */
    private function parseConstants(array $data)
    {
        $parsedData = array();

        foreach ($data as $key => $var) {
            if (is_array($var)) {
                $var = $this->parseConstants($var);
            } else {
                $var = $this->parser->parseConstants($var);
            }

            $parsedData[$key] = $var;
        }

        return $parsedData;
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
