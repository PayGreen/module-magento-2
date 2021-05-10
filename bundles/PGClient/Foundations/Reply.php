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
 * @version   2.0.2
 *
 */

/**
 * Class PGClientFoundationsReply
 * @package PGClient\Foundations
 */
abstract class PGClientFoundationsReply
{
    private $rawData;

    /**
     * PGClientFoundationsReply constructor.
     * @param array $data
     * @throws Exception
     */
    public function __construct($data)
    {
        $this->rawData = (array) $data;

        $this->compile();
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    protected function getRaw($name)
    {
        if (!$this->hasRaw($name)) {
            throw new Exception("Unknown property : '$name'.");
        }

        return $this->hasRaw($name) ? $this->rawData[$name] : null;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function hasRaw($name)
    {
        return array_key_exists($name, $this->rawData);
    }

    /**
     * @return array
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    abstract protected function compile();

    /**
     * @param $from
     * @param $to
     * @param string $type
     * @param bool $isRequired
     * @return $this
     * @throws Exception
     */
    protected function setScalar($from, $to, $type = 'string', $isRequired = true)
    {
        if ($isRequired && !$this->hasRaw($from)) {
            throw new PGClientExceptionsResponseMalformed("$from field is required in response.");
        }

        if ($this->hasRaw($from)) {
            $value = $this->getRaw($from);

            switch ($type) {
                case 'int':
                    $value = (int)$value;
                    break;
                case 'float':
                    $value = (float)$value;
                    break;
                case 'bool':
                    $value = (bool)$value;
                    break;
                case 'array':
                    $value = (array)$value;
                    break;
            }

            $this->{$to} = $value;
        }

        return $this;
    }

    /**
     * @param $from
     * @param $to
     * @param $class
     * @param bool $isRequired
     * @return $this
     * @throws Exception
     */
    protected function setObject($from, $to, $class, $isRequired = true)
    {
        if ($isRequired && !$this->hasRaw($from)) {
            throw new PGClientExceptionsResponseMalformed("$from field is required in response.");
        }

        if ($this->hasRaw($from)) {
            $this->{$to} = new $class($this->getRaw($from));
        }

        return $this;
    }
}
