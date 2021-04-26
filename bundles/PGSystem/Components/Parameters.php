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
 * Class PGSystemComponentsParameters
 * @package PGSystem\Components
 */
class PGSystemComponentsParameters implements arrayaccess
{
    /** @var PGSystemComponentsBag */
    private $bag;

    /** @var PGSystemComponentsParser */
    private $parser;

    /** @var string */
    private $source;

    private $bin;

    public function __construct()
    {
        $this->parser = new PGSystemComponentsParser(array());

        $this->buildParametersBag();
    }

    /**
     * @param string $source
     * @return self
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    private function buildParametersBag()
    {
        $this->bag = new PGSystemComponentsBag();
    }

    /**
     * @return PGSystemComponentsBag
     */
    public function getBag()
    {
        return $this->bag;
    }

    /**
     * @throws PGSystemExceptionsParserConstant
     */
    public function reset()
    {
        $this->buildParametersBag();

        $data = require($this->source);

        $data = $this->parseConstants($data);

        $this->bag->merge($data);
    }

    /**
     * @param array $data
     * @return array
     * @throws PGSystemExceptionsParserConstant
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
        // Thrashing unused arguments
        $this->bin = array($var, $value);

        throw new Exception("Can not manually add a parameter.");
    }
    public function offsetExists($var)
    {
        return isset($this->bag[$var]);
    }
    public function offsetUnset($var)
    {
        // Thrashing unused arguments
        $this->bin = $var;

        throw new Exception("Can not manually delete a parameter.");
    }
    public function offsetGet($var)
    {
        return $this->bag[$var];
    }
}
