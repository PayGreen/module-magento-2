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
 * @version   1.0.0
 */

/**
 * Class PGFrameworkFoundationsAbstractTask
 * @package PGFramework\Foundations
 */
abstract class PGFrameworkFoundationsAbstractSelector extends PGFrameworkFoundationsAbstractObject implements PGFrameworkInterfacesSelectorInterface
{
    private $choices = array();

    /** @var PGFrameworkServicesLogger */
    protected $logger;

    /** @var PGFrameworkServicesHandlersTranslatorHandler */
    protected $translatorHandler;

    public function __construct(PGFrameworkServicesLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param PGFrameworkServicesHandlersTranslatorHandler $translatorHandler
     */
    public function setTranslatorHandler(PGFrameworkServicesHandlersTranslatorHandler $translatorHandler)
    {
        $this->translatorHandler = $translatorHandler;
    }

    /**
     * @return PGFrameworkServicesHandlersTranslatorHandler
     */
    public function getTranslatorHandler()
    {
        return $this->translatorHandler;
    }

    /**
     * @param string $code
     * @return string
     */
    public function getName($code)
    {
        $choices = $this->getChoices();

        if (array_key_exists($code, $choices)) {
            $name = $choices[$code];
        } else {
            $name = $this->translate($code);
        }

        return $name;
    }

    public function getKeys()
    {
        return array_keys($this->getChoices());
    }

    /**
     * @return array
     */
    public function getChoices()
    {
        if (empty($this->choices)) {
            $this->choices = $this->buildChoices();
        }

        return $this->choices;
    }

    /**
     * @param array $choices
     */
    protected function setChoices(array $choices)
    {
        $this->choices = $choices;
    }

    /**
     * @param $code
     * @return string
     */
    protected function translate($code)
    {
        $root = $this->getTranslationRoot();

        $path = "$root.$code";

        if ($this->translatorHandler->has($path)) {
            $name = $this->translatorHandler->get($path);
        } else {
            $this->logger->warning("Label not found in '$path'.");
            $name = $code;
        }

        return $name;
    }

    /**
     * @return array
     */
    abstract protected function buildChoices();

    /**
     * @return string|null
     */
    protected function getTranslationRoot()
    {
        return null;
    }
}
