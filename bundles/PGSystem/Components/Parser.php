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
 * @version   2.5.1
 *
 */

namespace PGI\Module\PGSystem\Components;

use PGI\Module\PGSystem\Components\Service\Builder as BuilderServiceComponent;
use PGI\Module\PGSystem\Exceptions\ParserConstant as ParserConstantException;
use PGI\Module\PGSystem\Exceptions\ParserParameter as ParserParameterException;
use Exception;

/**
 * Class Parser
 * @package PGSystem\Components
 */
class Parser
{
    const REGEX_PARAMETER_MATCH = '/(^|[^\\\\])(?<match>%\{(?<key>[a-zA-Z0-9_\-\.]+)\})/';
    const REGEX_PARAMETER_REPLACE = '/(^|[^\\\\])(%%\{%s\})/';
    const REGEX_PARAMETER_CLEANING = '/(\\\\)(%\{[a-zA-Z0-9_\-\.]+\})/';

    const REGEX_ENV_VAR_MATCH = '/(^|[^\\\\])(?<match>\$\{(?<key>[a-zA-Z0-9_]+)\})/';
    const REGEX_ENV_VAR_REPLACE = '/(^|[^\\\\])(\$\{%s\})/';
    const REGEX_ENV_VAR_CLEANING = '/(\\\\)(\$\{[a-zA-Z0-9_]+\})/';

    /** @var iterable */
    private $parameters;

    /** @var BuilderServiceComponent */
    private $builder;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param BuilderServiceComponent $builder
     */
    public function setBuilder($builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param string $arg
     * @return array|bool|mixed|object|string
     * @throws Exception
     */
    public function parseAll($arg)
    {
        $arg = $this->parseStringParameters($arg);
        $arg = $this->parseConstants($arg);
        $arg = $this->parseParameter($arg);
        $arg = $this->injectService($arg);

        return $arg;
    }

    /**
     * @param string|array $arg
     * @return array
     * @throws ParserConstantException
     * @throws ParserParameterException
     */
    public function parseConfig($arg)
    {
        if (is_array($arg)) {
            foreach ($arg as $key => $val) {
                $arg[$key] = $this->parseConfig($val);
            }
        } else {
            $arg = $this->parseStringParameters($arg);
            $arg = $this->parseConstants($arg);
            $arg = $this->parseParameter($arg);
        }

        return $arg;
    }

    /**
     * @param mixed $var
     * @return mixed
     * @throws Exception
     */
    public function injectService($var)
    {
        if (is_string($var)) {
            if (substr($var, 0, 1) === '@') {
                $serviceName = substr($var, 1);
                $var = $this->builder->build($serviceName);
            }
        }

        return $var;
    }

    /**
     * @param string $var
     * @param array $values
     * @return string
     * @throws ParserParameterException
     */
    public function parseStringParameters($var, array $values = array())
    {
        if (is_string($var)) {
            while (preg_match(self::REGEX_PARAMETER_MATCH, $var, $matches)) {
                $key = $matches['key'];

                if (isset($this->parameters[$key])) {
                    $value = $this->parameters[$key];
                } elseif (array_key_exists($key, $values)) {
                    $value = $values[$key];
                } else {
                    throw new ParserParameterException("Target parameters '$key' is not defined.");
                }

                $pattern = sprintf(self::REGEX_PARAMETER_REPLACE, preg_quote($key));
                $var = preg_replace($pattern, '${1}' . $value, $var);
            }

            $var = preg_replace(self::REGEX_PARAMETER_CLEANING, '$2', $var);
        }

        return $var;
    }

    /**
     * @param mixed $var
     * @return mixed
     * @throws ParserParameterException
     */
    public function parseParameter($var)
    {
        if (is_string($var)) {
            if (substr($var, 0, 1) === '%') {
                $key = substr($var, 1);

                if (!isset($this->parameters[$key])) {
                    throw new ParserParameterException("Target parameters '$key' is not defined.");
                }

                $var = $this->parameters[$key];
            }
        }

        return $var;
    }

    /**
     * @param string $var
     * @return string
     * @throws ParserConstantException
     */
    public function parseConstants($var)
    {
        if (is_string($var)) {
            while (preg_match(self::REGEX_ENV_VAR_MATCH, $var, $matches)) {
                $key = $matches['key'];

                if (!defined($key)) {
                    throw new ParserConstantException("Target constant '$key' is not defined.");
                }

                $pattern = sprintf(self::REGEX_ENV_VAR_REPLACE, preg_quote($key));
                $var = preg_replace($pattern, '${1}' . constant($key), $var);
            }

            $var = preg_replace(self::REGEX_ENV_VAR_CLEANING, '$2', $var);
        }

        return $var;
    }
}
