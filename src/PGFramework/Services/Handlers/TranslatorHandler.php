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
 * @version   0.3.5
 */

/**
 * Class PGFrameworkServicesHandlersTranslatorHandler
 * @package PGFramework\Services
 */
class PGFrameworkServicesHandlersTranslatorHandler extends PGFrameworkFoundationsAbstractObject
{
    private $defaultLocal;

    private $local;

    private $sources;

    /** @var PGFrameworkServicesHandlersCacheHandler */
    private $cacheHandler;

    /** @var PGFrameworkServicesPathfinder */
    private $pathfinder;

    private $translations = array();

    const REGEX_TRANSLATION_KEY = "/^[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]*)*$/";

    /**
     * PGFrameworkServicesHandlersTranslatorHandler constructor.
     * @param PGFrameworkServicesHandlersCacheHandler $cacheHandler
     * @param PGFrameworkServicesPathfinder $pathfinder
     * @param array $config
     * @throws PGFrameworkExceptionsConfigurationException
     */
    public function __construct(
        PGFrameworkServicesHandlersCacheHandler $cacheHandler,
        PGFrameworkServicesPathfinder $pathfinder,
        array $config
    ) {
        if (!array_key_exists('local', $config)) {
            $message = "Translator configuration should contains 'local' parameter.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!is_string($config['local'])) {
            $message = "Translator configuration 'local' parameter should be a string.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!array_key_exists('default_local', $config)) {
            $message = "Translator configuration should contains 'default_local' parameter.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!is_string($config['default_local'])) {
            $message = "Translator configuration 'default_local' parameter should be a string.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!array_key_exists('sources', $config)) {
            $message = "Translator configuration should contains 'sources' parameter.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!is_array($config['sources'])) {
            $message = "Translator configuration 'sources' parameter should be an array.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        }

        $this->cacheHandler = $cacheHandler;
        $this->pathfinder = $pathfinder;

        $this->local = $config['local'];
        $this->defaultLocal = $config['default_local'];
        $this->sources = $config['sources'];
    }

    protected function getTranslation($key, $local, $isStrict = false)
    {
        if (!array_key_exists($local, $this->translations)) {
            $this->translations[$local] = $this->loadTranslations($local);
        }

        if (array_key_exists($key, $this->translations[$local])) {
            return $this->translations[$local][$key];
        } elseif ($isStrict) {
            $this->getService('logger')->warning("Missing translation for local '$local' : '$key'.");
        }
    }

    protected function loadTranslations($local)
    {
        $cacheName = 'translations-' . strtolower($local);

        $translations = $this->cacheHandler->loadEntry($cacheName);

        if (($translations === null) || (PAYGREEN_ENV === 'DEV')) {
            $translations = $this->buildTranslations($local);

            $this->cacheHandler->saveEntry($cacheName, $translations);
        }

        $this->getService('logger')->debug("Translations loaded for local : '$local'.");

        return $translations;
    }

    protected function buildTranslations($local)
    {
        $translations = array();

        foreach ($this->sources as $source) {
            $path = $this->pathfinder->toAbsolutePath($source, '/translations/' . strtolower($local));

            if (is_dir($path)) {
                foreach (glob($path . DIRECTORY_SEPARATOR . '*.json') as $filename) {
                    $data = json_decode(file_get_contents($filename), true);
                    $this->flatenize($translations, $data);
                }
            }
        }

        return $translations;
    }

    protected function flatenize(array &$translations, array $data, $base = null)
    {
        foreach ($data as $key => $val) {
            $basedKey = $base ? "$base.$key" : $key;

            if (is_array($val) && !PGFrameworkToolsArray::isSequential($val)) {
                $this->flatenize($translations, $val, $basedKey);
            } else {
                $translations[$basedKey] = $val;
            }
        }
    }

    public function get($key, array $values = array(), $local = null)
    {
        $local = ($local === null) ? $this->local : $local;

        if (preg_match(self::REGEX_TRANSLATION_KEY, $key)) {
            $translation = $this->getTranslation($key, $local);

            if (is_null($translation)) {
                if ($local !== $this->defaultLocal) {
                    $translation = $this->get($key, $values, $this->defaultLocal);
                } else {
                    $translation = "Missing translation";
                }
            }
        } else {
            $this->getService('logger')->warning("Unrecognized translation key : '$key'.");

            $translation = $key;
        }

        return $translation;
    }

    public function has($key, $local = null)
    {
        $local = ($local === null) ? $this->local : $local;

        $result = false;

        if (preg_match(self::REGEX_TRANSLATION_KEY, $key)) {
            $translation = $this->getTranslation($key, $local, false);

            if (is_null($translation)) {
                if ($local !== $this->defaultLocal) {
                    $result = $this->has($key, $this->defaultLocal);
                }
            } else {
                $result = true;
            }
        } else {
            $this->getService('logger')->warning("Unrecognized translation key : '$key'.");
        }

        return $result;
    }
}
