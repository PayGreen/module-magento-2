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
 * @version   1.1.0
 */

/**
 * Class PGIntlServicesTranslator
 * @package PGFramework\Services
 */
class PGIntlServicesTranslator extends PGFrameworkFoundationsAbstractObject
{
    private $defaultLanguage;

    private $language;

    private $sources;

    /** @var PGIntlServicesHandlersCacheTranslationHandler */
    private $cacheHandler;

    /** @var PGIntlServicesHandlersLocaleHandler */
    private $localeHandler;

    /** @var PGFrameworkServicesPathfinder */
    private $pathfinder;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    private $translations = array();

    const REGEX_TRANSLATION_KEY = "/^[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]*)*$/";

    /**
     * PGIntlServicesTranslator constructor.
     * @param PGIntlServicesHandlersCacheTranslationHandler $cacheHandler
     * @param PGFrameworkServicesPathfinder $pathfinder
     * @param PGIntlServicesHandlersLocaleHandler $localeHandler
     * @param PGFrameworkServicesLogger $logger
     * @param array $config
     * @throws PGFrameworkExceptionsConfigurationException
     */
    public function __construct(
        PGIntlServicesHandlersCacheTranslationHandler $cacheHandler,
        PGFrameworkServicesPathfinder $pathfinder,
        PGIntlServicesHandlersLocaleHandler $localeHandler,
        PGFrameworkServicesLogger $logger,
        array $config
    ) {
        if (!array_key_exists('sources', $config)) {
            $message = "Translator configuration should contains 'sources' parameter.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        } elseif (!is_array($config['sources'])) {
            $message = "Translator configuration 'sources' parameter should be an array.";
            throw new PGFrameworkExceptionsConfigurationException($message);
        }

        $this->cacheHandler = $cacheHandler;
        $this->pathfinder = $pathfinder;
        $this->localeHandler = $localeHandler;
        $this->logger = $logger;

        $this->language = $localeHandler->getLanguage();
        $this->defaultLanguage = $localeHandler->getDefaultLanguage();
        $this->sources = $config['sources'];
    }

    protected function getTranslation($key, $language, $isStrict = false)
    {
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
            foreach (glob($path . DIRECTORY_SEPARATOR . '*.json') as $filename) {
                $data = json_decode(file_get_contents($filename), true);

                if ($data === null) {
                    throw new Exception("Invalid translation file : '$filename'.");
                }

                $this->flatenize($translations, $data);
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

    /**
     * @param string|PGIntlComponentsTranslation $translation
     * @param string|null $language
     * @return string
     * @throws Exception
     */
    public function get($translation, $language = null)
    {
        try {
            if (!is_object($translation)) {
                $translation = new PGIntlComponentsTranslation($translation);
            }

            if (!($translation instanceof PGIntlComponentsTranslation)) {
                throw new Exception("Bad format for translation component.");
            }

            if (substr($translation->getKey(), 0, 1) === '~') {
                return $this->getCustomTranslation(substr($translation->getKey(), 1));
            }

            $language = ($language === null) ? $this->language : $language;

            if (preg_match(self::REGEX_TRANSLATION_KEY, $translation->getKey())) {
                $translatedText = $this->getTranslation($translation->getKey(), $language);

                if (is_null($translatedText)) {
                    if ($language !== $this->defaultLanguage) {
                        $translatedText = $this->get($translation, $this->defaultLanguage);
                    } else {
                        $translatedText = "Missing translation";
                    }
                }

                if (!is_null($translatedText) && $translation->hasData()) {
                    try {
                        $parser = new PGFrameworkComponentsParser($translation->getData());

                        $translatedText = $parser->parseStringParameters($translatedText);
                    } catch (PGFrameworkExceptionsParserParameterException $exception) {
                        $this->logger->warning("Missing data for translation '{$translation->getKey()}'.", $exception);
                        $translatedText = "Invalid translation";
                    }
                }
            } else {
                $this->logger->warning("Unrecognized translation key : '{$translation->getKey()}'.");

                $translatedText = $translation->getKey();
            }
        } catch (Exception $exception) {
            $this->logger->error("Error during translation for key '{$translation->getKey()}' : " . $exception->getMessage(), $exception);

            $translatedText = "Failed translation";
        }

        return $translatedText;
    }

    protected function getCustomTranslation($key)
    {
        /** @var PGIntlServicesHandlersTranslationHandler $translationHandler */
        $translationHandler = $this->getService('handler.translation');

        return $translationHandler->translate($key);
    }

    /**
     * @param string|PGIntlComponentsTranslation $translation
     * @param string|null $language
     * @return bool
     * @throws Exception
     */
    public function has($translation, $language = null)
    {
        if (!is_object($translation)) {
            $translation = new PGIntlComponentsTranslation($translation);
        }

        if (!($translation instanceof PGIntlComponentsTranslation)) {
            throw new Exception("Bad format for translation component.");
        }

        $language = ($language === null) ? $this->language : $language;

        $result = false;

        if (preg_match(self::REGEX_TRANSLATION_KEY, $translation->getKey())) {
            $translatedText = $this->getTranslation($translation->getKey(), $language, false);

            if (is_null($translatedText)) {
                if ($language !== $this->defaultLanguage) {
                    $result = $this->has($translation, $this->defaultLanguage);
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
