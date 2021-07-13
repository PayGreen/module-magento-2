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
 * @version   2.1.1
 *
 */

/**
 * Class PGIntlServicesTranslator
 * @package PGIntl\Services
 */
class PGIntlServicesTranslator extends PGSystemFoundationsObject
{
    private $sources;

    /** @var PGIntlServicesHandlersCacheTranslationHandler */
    private $cacheHandler;

    /** @var PGIntlServicesHandlersLocaleHandler */
    private $localeHandler;

    /** @var PGSystemServicesPathfinder */
    private $pathfinder;

    /** @var PGModuleServicesLogger */
    private $logger;

    private $translations = array();

    private $bin;

    const DEFAULT_TRANSLATION_LANGUAGE = 'en';

    const REGEX_TRANSLATION_KEY = "/^[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]*)*$/";

    /**
     * PGIntlServicesTranslator constructor.
     * @param PGIntlServicesHandlersCacheTranslationHandler $cacheHandler
     * @param PGSystemServicesPathfinder $pathfinder
     * @param PGIntlServicesHandlersLocaleHandler $localeHandler
     * @param PGModuleServicesLogger $logger
     * @param array $config
     * @throws PGSystemExceptionsConfiguration
     */
    public function __construct(
        PGIntlServicesHandlersCacheTranslationHandler $cacheHandler,
        PGSystemServicesPathfinder $pathfinder,
        PGIntlServicesHandlersLocaleHandler $localeHandler,
        PGModuleServicesLogger $logger,
        array $config
    ) {
        if (!array_key_exists('sources', $config)) {
            $message = "Translator configuration should contains 'sources' parameter.";
            throw new PGSystemExceptionsConfiguration($message);
        } elseif (!is_array($config['sources'])) {
            $message = "Translator configuration 'sources' parameter should be an array.";
            throw new PGSystemExceptionsConfiguration($message);
        }

        $this->cacheHandler = $cacheHandler;
        $this->pathfinder = $pathfinder;
        $this->localeHandler = $localeHandler;
        $this->logger = $logger;

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

            if (is_array($val) && !PGSystemToolsArray::isSequential($val)) {
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
        // Thrashing unused arguments
        $this->bin = $language;

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

            $translatedText = $this->translate($translation);
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
     * @param PGIntlComponentsTranslation $translation
     * @return string
     */
    protected function translate(PGIntlComponentsTranslation $translation)
    {
        if (preg_match(self::REGEX_TRANSLATION_KEY, $translation->getKey())) {
            $languages = array_unique(array(
                $this->localeHandler->getLanguage(),
                $this->localeHandler->getDefaultLanguage(),
                self::DEFAULT_TRANSLATION_LANGUAGE
            ));

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
                    $parser = new PGSystemComponentsParser($translation->getData());
                    $translatedText = $parser->parseStringParameters($translatedText);
                } catch (PGSystemExceptionsParserParameter $exception) {
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
