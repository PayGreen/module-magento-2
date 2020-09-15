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
 * Class PGIntlServicesManagersTranslationManager
 *
 * @package PGFramework\Services\Managers
 * @method PGIntlInterfacesRepositoriesTranslationRepositoryInterface getRepository()
 */
class PGIntlServicesManagersTranslationManager extends PGFrameworkFoundationsAbstractManager
{
    public function getByCode($code, $fieldFormat = false)
    {
        $translations = $this->getRepository()->findByCode($code);

        $texts = $this->groupTextsByCode($translations);

        $text = array_key_exists($code, $texts) ? $texts[$code] : array();

        return $fieldFormat ? $this->toFieldFormat($text) : $text;
    }

    public function getByPattern($pattern, $fieldFormat = false)
    {
        $translations = $this->getRepository()->findByPattern($pattern);

        return $this->groupTextsByCode($translations);
    }

    protected function fromFieldFormat(array $texts)
    {
        $translations = array();

        foreach ($texts as $text) {
            if (
                is_array($text) &&
                array_key_exists('language', $text) &&
                array_key_exists('text', $text) &&
                !empty($text['language']) &&
                !empty($text['text'])
            ) {
                $translations[$text['language']] = $text['text'];
            }
        }

        return $translations;
    }

    protected function toFieldFormat(array $texts)
    {
        $data = array();

        foreach($texts as $language => $text) {
            $data[] = array(
                'text' => $text,
                'language' => $language
            );
        }

        return $data;
    }

    protected function groupTextsByCode(array $translations)
    {
        $texts = array();

        /** @var PGIntlInterfacesEntitiesTranslationInterface $translation */
        foreach ($translations as $translation) {
            $code = $translation->getCode();
            $language = $translation->getLanguage();

            if (!array_key_exists($code, $texts)) {
                $texts[$code] = array();
            }

            $texts[$code][$language] = $translation->getText();
        }

        return $texts;
    }

    public function saveByCode($code, array $texts, PGDomainInterfacesEntitiesShopInterface $shop = null, $fieldFormat = false)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $translations = $this->getRepository()->findByCode($code, $shop);

        /** @var PGIntlInterfacesEntitiesTranslationInterface[] $translations */
        $translations = $this->groupTranslationsByLanguage($translations);

        $texts = $fieldFormat ? $this->fromFieldFormat($texts) : $texts;

        foreach($texts as $language => $text) {
            if (array_key_exists($language, $translations)) {
                $translations[$language]->setText($text);

                $logger->debug("Updating '$language' translation for code '$code'.");

                if (!$this->getRepository()->update($translations[$language])) {
                    throw new Exception("Unable to update '$language' text for '$code' translation.");
                }

                unset($translations[$language]);
            } else {
                /** @var PGIntlInterfacesEntitiesTranslationInterface $translation */
                $translation = $this->getRepository()->create($code, $language, $shop);

                $logger->debug("Creating '$language' translation for code '$code'.");

                $translation->setText($text);

                if (!$this->getRepository()->insert($translation)) {
                    throw new Exception("Unable to insert '$language' text for '$code' translation.");
                }
            }
        }

        /** @var PGIntlInterfacesEntitiesTranslationInterface $translation */
        foreach($translations as $translation) {
            $language = $translation->getLanguage();

            $logger->debug("Deleting '$language' translation for code '$code'.");

            if (!$this->getRepository()->delete($translation)) {
                throw new Exception("Unable to delete '$language' text for '$code' translation.");
            }
        }
    }

    public function deleteByCode($code)
    {
        /** @var PGFrameworkServicesLogger $logger */
        $logger = $this->getService('logger');

        $translations = $this->getRepository()->findByCode($code);

        /** @var PGIntlInterfacesEntitiesTranslationInterface $translation */
        foreach($translations as $translation) {
            $language = $translation->getLanguage();

            $logger->debug("Deleting '$language' translation for code '$code'.");

            if (!$this->getRepository()->delete($translation)) {
                throw new Exception("Unable to delete '$language' text for '$code' translation.");
            }
        }

        return true;
    }

    protected function groupTranslationsByLanguage(array $translations)
    {
        $texts = array();

        /** @var PGIntlInterfacesEntitiesTranslationInterface $translation */
        foreach ($translations as $translation) {
            $language = $translation->getLanguage();

            if (!array_key_exists($language, $texts)) {
                $texts[$language] = array();
            }

            $texts[$language] = $translation;
        }

        return $texts;
    }
}
