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

namespace PGI\Module\PGFramework\Services;

use PGI\Module\PGSystem\Foundations\AbstractObject;
use Exception;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

/**
 * Class Dumper
 * @package PGFramework\Services
 */
class Dumper extends AbstractObject
{
    const MAX_LEVEL_NESTED = 5;
    const MAX_ARRAY_ITEMS = 5;

    public function toString($data)
    {
        if (is_object($data) && ($data instanceof Exception)) {
            return $this->sanitizeException($data);
        } else {
            return print_r($this->sanitizeData($data), true);
        }
    }

    public function toMixed($data)
    {
        return $this->sanitizeData($data);
    }

    protected function sanitizeException(Exception $exception)
    {
        $result = "Exception data :" . PHP_EOL;

        $result .= $this->formatException($exception);

        return $result;
    }

    protected function formatException(Exception $exception)
    {
        $class = get_class($exception);

        $result = <<<EOT
Message : {$exception->getMessage()}
Type : $class
Code : {$exception->getCode()}
File : {$exception->getFile()}
Line : {$exception->getLine()}

Stack trace :
{$exception->getTraceAsString()}

EOT;

        if ($exception->getPrevious() !== null) {
            $result .= <<<EOT

---------------------------------------------
Previous exception :

EOT;
            $result .= $this->formatException($exception->getPrevious());
        }

        return $result;
    }

    protected function sanitizeData($data, $level = 0)
    {
        if (is_null($data)) {
            return '(null) NULL';
        } elseif (is_bool($data)) {
            return $data ? '(bool) TRUE' : '(bool) FALSE';
        } elseif (is_string($data)) {
            return "(string) $data";
        } elseif (is_int($data)) {
            return "(int) $data";
        } elseif (is_float($data)) {
            return "(float) $data";
        } elseif (is_resource($data)) {
            return "(resource) " . get_resource_type($data);
        } elseif (is_array($data)) {
            if (empty($data)) {
                $result = "(array) empty";
            } elseif ($level >= self::MAX_LEVEL_NESTED) {
                $result = "(array) not empty";
            } else {
                $result = $this->formatArray($data, $level);
            }

            return $result;
        } elseif (is_object($data)) {
            return $this->formatObject($data, $level);
        } else {
            return $data;//"Unsupported data type.";
        }
    }

    protected function formatArray($data, $level)
    {
        $result = array();
        $count = 0;
        $isNumeric = array_keys($data) === range(0, count($data));

        foreach ($data as $key => $val) {
            $count ++;
            $result[$key] = $this->sanitizeData($val, $level + 1);

            if ($isNumeric && ($count >= self::MAX_ARRAY_ITEMS)) {
                break;
            }
        }

        return $result;
    }

    protected function formatObject($data, $level)
    {
        $reflection = new ReflectionObject($data);

        $result = array();

        $result['class'] = "Instance of : " . get_class($data);

        /** @var ReflectionClass $parent */
        $parent = $reflection->getParentClass();
        if ($parent) {
            $result['parent'] = "Extends : " . $parent->getName();
        }

        $interfaces = $reflection->getInterfaceNames();
        if (!empty($interfaces)) {
            $result['interfaces'] = $interfaces;
        }

        if ($level >= self::MAX_LEVEL_NESTED) {
            $sanitizedProperties = "Not traveled.";
        } else {
            $sanitizedProperties = array();
            $properties = $reflection->getProperties();

            /** @var ReflectionProperty $property */
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $value = $property->getValue($data);

                $sanitizedProperties[$property->getName()] = $this->sanitizeData($value, $level + 1);
            }
        }

        $result['properties'] = $sanitizedProperties;

        return $result;
    }
}
