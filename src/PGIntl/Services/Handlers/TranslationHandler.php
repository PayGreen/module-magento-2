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
 * @version   1.2.5
 *
 */

/**
 * Class PGIntlServicesHandlersTranslationHandler
 * @package PGFramework\Services
 */
class PGIntlServicesHandlersTranslationHandler extends PGFrameworkFoundationsAbstractObject
{
    private $config = array();

    /** @var PGIntlServicesManagersTranslationManager */
    private $translationManager;

    /** @var PGIntlServicesHandlersLocaleHandler */
    private $localeHandler;

    /** @var PGFrameworkServicesLogger */
    private $logger;

    /**
     * PGIntlServicesHandlersTranslationHandler constructor.
     * @param PGIntlServicesManagersTranslationManager $translationManager
     * @param PGIntlServicesHandlersLocaleHandler $localeHandler
     * @param PGFrameworkServicesLogger $logger
     * @param array $config
     */
    public function __construct(
        PGIntlServicesManagersTranslationManager $translationManager,
        PGIntlServicesHandlersLocaleHandler $localeHandler,
        PGFrameworkServicesLogger $logger,
        array $config
    ) {
        $this->translationManager = $translationManager;
        $this->localeHandler = $localeHandler;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param string $code
     * @return string
     */
    public function translate($code)
    {
        $language = $this->localeHandler->getLanguage();
        $defaultLanguage = $this->localeHandler->getDefaultLanguage();
        $translations = $this->translationManager->getByCode($code);

        if (array_key_exists($language, $translations)) {
            $text = $translations[$language];
        } elseif (array_key_exists($defaultLanguage, $translations)) {
            $text = $translations[$defaultLanguage];
        } else {
            $text = "Translation not found";
            $this->logger->warning("Translation not found : '$code'.");
        }

        return $text;
    }

    /**
     * @param bool $fieldFormat
     * @return array
     */
    public function getTranslations($fieldFormat = false)
    {
        $translations = array();

        foreach($this->config as $code => $config) {
            if (!array_key_exists('enabled', $config) || $config['enabled']) {
                $translations[$code] = $config;
                $translations[$code]['texts'] = $this->translationManager->getByCode($code, $fieldFormat);
            }
        }

        return $translations;
    }

    /**
     * @return array
     */
    public function getCodes()
    {
        $codes = array();

        foreach($this->config as $code => $config) {
            if (!array_key_exists('enabled', $config) || $config['enabled']) {
                $codes[] = $code;
            }
        }

        return $codes;
    }

    /**
     * @param PGDomainInterfacesEntitiesShopInterface|null $shop
     * @param array $codes
     * @return bool
     */
    public function insertDefaultTranslations(PGDomainInterfacesEntitiesShopInterface $shop = null, array $codes = array())
    {
        $this->logger->notice("Install default translations.");

        $result = false;

        try {
            $translations = $this->getTranslations();

            foreach ($translations as $code => $config) {
                $isExpected = (in_array($code, $codes) || empty($codes));
                $hasDefault = array_key_exists('default', $config);

                if ($isExpected && $hasDefault) {
                    $this->logger->debug("Insert '$code' default translation.", $config['default']);

                    $this->translationManager->saveByCode($code, $config['default'], $shop);
                }
            }

            $result = true;
        } catch (Exception $exception) {
            $this->logger->error("An error occurred during default translation installation : " . $exception->getMessage(), $exception);
        }

        return $result;
    }
}
