<?php
/**
 * 2014 - 2020 Watt Is It
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
 * @copyright 2014 - 2020 Watt Is It
 * @license   https://creativecommons.org/licenses/by-nd/4.0/fr/ Creative Commons BY-ND 4.0
 * @version   1.1.1
 */

/**
 * Class PGFrameworkComponentsParser
 * @package PGFramework\Components
 */
class PGFrameworkComponentsParser
{
    const REGEX_PARAMETER_MATCH = '/(^|[^\\\\])(?<match>%\{(?<key>[a-zA-Z0-9_\-\.]+)\})/';
    const REGEX_PARAMETER_REPLACE = '/(^|[^\\\\])(%%\{%s\})/';
    const REGEX_PARAMETER_CLEANING = '/(\\\\)(%\{[a-zA-Z0-9_\-\.]+\})/';

    const REGEX_ENV_VAR_MATCH = '/(^|[^\\\\])(?<match>\$\{(?<key>[a-zA-Z0-9_]+)\})/';
    const REGEX_ENV_VAR_REPLACE = '/(^|[^\\\\])(\$\{%s\})/';
    const REGEX_ENV_VAR_CLEANING = '/(\\\\)(\$\{[a-zA-Z0-9_]+\})/';

    /** @var iterable */
    private $parameters;

    /** @var PGFrameworkComponentsServiceBuilder */
    private $builder;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param PGFrameworkComponentsServiceBuilder $builder
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
     * @throws PGFrameworkExceptionsParserParameterException
     */
    public function parseStringParameters($var, array $values = array())
    {
        if (is_string($var)) {
            while (preg_match(self::REGEX_PARAMETER_MATCH, $var, $matches)) {
                $key = $matches['key'];

                if (isset($this->parameters[$key])) {
                    $value = $this->parameters[$key];
                } elseif (array_keys($key, $values)) {
                    $value = $values[$key];
                } else {
                    throw new PGFrameworkExceptionsParserParameterException("Target parameters '$key' is not defined.");
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
     * @throws PGFrameworkExceptionsParserParameterException
     */
    public function parseParameter($var)
    {
        if (is_string($var)) {
            if (substr($var, 0, 1) === '%') {
                $key = substr($var, 1);

                if (!isset($this->parameters[$key])) {
                    throw new PGFrameworkExceptionsParserParameterException("Target parameters '$key' is not defined.");
                }

                $var = $this->parameters[$key];
            }
        }

        return $var;
    }

    /**
     * @param string $var
     * @return string
     * @throws PGFrameworkExceptionsParserConstantException
     */
    public function parseConstants($var)
    {
        if (is_string($var)) {
            while (preg_match(self::REGEX_ENV_VAR_MATCH, $var, $matches)) {
                $key = $matches['key'];

                if (!defined($key)) {
                    throw new PGFrameworkExceptionsParserConstantException("Target constant '$key' is not defined.");
                }

                $pattern = sprintf(self::REGEX_ENV_VAR_REPLACE, preg_quote($key));
                $var = preg_replace($pattern, '${1}' . constant($key), $var);
            }

            $var = preg_replace(self::REGEX_ENV_VAR_CLEANING, '$2', $var);
        }

        return $var;
    }
}
