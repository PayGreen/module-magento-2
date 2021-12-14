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
 * @version   2.5.0
 *
 */

namespace PGI\Module\PGIntl\Services;

use PGI\Module\PGIntl\Components\Translation as TranslationComponent;
use PGI\Module\PGIntl\Services\Handlers\CacheTranslationHandler;
use PGI\Module\PGIntl\Services\Handlers\LocaleHandler;
use PGI\Module\PGIntl\Services\Handlers\TranslationHandler;
use PGI\Module\PGModule\Services\Logger;
use PGI\Module\PGSystem\Components\Bag;
use PGI\Module\PGSystem\Components\Parser as ParserComponent;
use PGI\Module\PGSystem\Exceptions\Configuration as ConfigurationException;
use PGI\Module\PGSystem\Exceptions\ParserParameter as ParserParameterException;
use PGI\Module\PGSystem\Foundations\AbstractObject;
use PGI\Module\PGSystem\Services\Pathfinder;
use PGI\Module\PGSystem\Tools\Collection as CollectionTool;
use Exception;

/**
 * Class Translator
 * @package PGIntl\Services
 */
class Translator extends AbstractObject
{
    /** @var Bag */
    private $config;

    /** @var ParserComponent */
    private $parser;

    /** @var CacheTranslationHandler */
    private $cacheHandler;

    /** @var LocaleHandler */
    private $localeHandler;

    /** @var Pathfinder */
    private $pathfinder;

    /** @var Logger */
    private $logger;

    private $translations = array();

    const DEFAULT_TRANSLATION_LANGUAGE = 'en';

    const REGEX_TRANSLATION_KEY = "/^[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]*)*$/";

    /**
     * Translator constructor.
     * @param CacheTranslationHandler $cacheHandler
     * @param Pathfinder $pathfinder
     * @param LocaleHandler $localeHandler
     * @param Logger $logger
     * @param array $config
     * @throws ConfigurationException
     */
    public function __construct(
        CacheTranslationHandler $cacheHandler,
        Pathfinder $pathfinder,
        LocaleHandler $localeHandler,
        Logger $logger,
        array $config
    ) {
        $this->cacheHandler = $cacheHandler;
        $this->pathfinder = $pathfinder;
        $this->localeHandler = $localeHandler;
        $this->logger = $logger;

        $this->config = new Bag($config);
        $this->parser = new ParserComponent(array());
    }

    protected function getTranslation($key, $language, $isStrict = false)
    {
        if (!in_array($language, $this->config["native_languages"])) {
            return null;
        }

        $translatedText = null;

        if (!array_key_exists($language, $this->translations)) {
            $this->translations[$language] = $this->loadTranslations($language);
        }

        if (array_key_exists($key, $this->translations[$language])) {
            $translatedText = $this->translations[$language][$key];
        } elseif ($isStrict) {
            $this->logger->warning("Missing translation for language '$language' : '$key'.");
        }

        return $translatedText;
    }

    protected function loadTranslations($language)
    {
        $translations = $this->cacheHandler->load($language);

        if ($translations === null) {
            $translations = $this->buildTranslations($language);

            $this->cacheHandler->save($language, $translations);
        }

        $this->logger->debug("Translations loaded for language : '$language'.");

        return $translations;
    }

    protected function buildTranslations($language)
    {
        $translations = array();

        $paths = $this->pathfinder->reviewVendorPaths('/_resources/translations/' . strtolower($language));

        foreach ($paths as $path) {
            $this->handleEachTranslationFile($translations, $path);
        }

        return $translations;
    }

    protected function handleEachTranslationFile(array &$translations, $path)
    {
        foreach (glob($path . DIRECTORY_SEPARATOR . '*.json') as $filename) {
            $data = json_decode(file_get_contents($filename), true);

            if ($data === null) {
                throw new Exception("Invalid translation file : '$filename'.");
            }

            $this->flatenize($translations, $data);
        }

        foreach (glob($path . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR) as $subDirectory) {
            $this->handleEachTranslationFile($translations, $subDirectory);
        }
    }

    protected function flatenize(array &$translations, array $data, $base = null)
    {
        foreach ($data as $key => $val) {
            $basedKey = $base ? "$base.$key" : $key;

            if (is_array($val) && !CollectionTool::isSequential($val)) {
                $this->flatenize($translations, $val, $basedKey);
            } else {
                $translations[$basedKey] = $val;
            }
        }
    }

    /**
     * @param string|TranslationComponent $translation
     * @param string|null $language
     * @return string
     * @throws Exception
     */
    public function get($translation, $language = null)
    {
        try {
            if (!is_object($translation)) {
                $translation = new TranslationComponent($translation);
            }

            if (!($translation instanceof TranslationComponent)) {
                throw new Exception("Bad format for translation component.");
            }

            if (substr($translation->getKey(), 0, 1) === '~') {
                return $this->getCustomTranslation(substr($translation->getKey(), 1));
            }

            $translatedText = $this->translate($translation, $language);
        } catch (Exception $exception) {
            $this->logger->error(
                "Error during translation for key '{$translation->getKey()}' : " . $exception->getMessage(),
                $exception
            );

            $translatedText = "Failed translation";
        }

        return $translatedText;
    }

    /**
     * @param TranslationComponent $translation
     * @param string|null $language
     * @return string
     */
    protected function translate(TranslationComponent $translation, $language = null)
    {
        if (preg_match(self::REGEX_TRANSLATION_KEY, $translation->getKey())) {
            if ($language === null) {
                $languages = array_unique(array(
                    $this->localeHandler->getLanguage(),
                    $this->localeHandler->getDefaultLanguage(),
                    self::DEFAULT_TRANSLATION_LANGUAGE
                ));
            } else {
                $languages = array_unique(array(
                    $language,
                    self::DEFAULT_TRANSLATION_LANGUAGE
                ));
            }

            $translatedText = null;

            foreach ($languages as $language) {
                $translatedText = $this->getTranslation($translation->getKey(), $language);
                if (!is_null($translatedText)) {
                    break;
                }
            }

            if (is_null($translatedText)) {
                $translatedText = "Missing translation";
            } elseif ($translation->hasData()) {
                try {
                    $translatedText = $this->parser->parseStringParameters($translatedText, $translation->getData());
                } catch (ParserParameterException $exception) {
                    $this->logger->warning("Missing data for translation '{$translation->getKey()}'.", $exception);
                    $translatedText = "Invalid translation";
                }
            }
        } else {
            $this->logger->warning("Unrecognized translation key : '{$translation->getKey()}'.");

            $translatedText = $translation->getKey();
        }

        return $translatedText;
    }

    protected function getCustomTranslation($key)
    {
        /** @var TranslationHandler $translationHandler */
        $translationHandler = $this->getService('handler.translation');

        return $translationHandler->translate($key);
    }

    /**
     * @param string|TranslationComponent $translation
     * @param string|null $language
     * @return bool
     * @throws Exception
     */
    public function has($translation, $language = null)
    {
        if (!is_object($translation)) {
            $translation = new TranslationComponent($translation);
        }

        if (!($translation instanceof TranslationComponent)) {
            throw new Exception("Bad format for translation component.");
        }

        $language = ($language === null) ? $this->localeHandler->getLanguage() : $language;

        $result = false;

        if (preg_match(self::REGEX_TRANSLATION_KEY, $translation->getKey())) {
            $translatedText = $this->getTranslation($translation->getKey(), $language, false);
            $shopLanguage = $this->localeHandler->getDefaultLanguage();

            if (is_null($translatedText)) {
                if ($language !== $shopLanguage) {
                    $result = $this->has($translation, $shopLanguage);
                }
            } else {
                $result = true;
            }
        } else {
            $this->logger->warning("Unrecognized translation key : '{$translation->getKey()}'.");
        }

        return $result;
    }
}
